#!/usr/bin/env python3
"""
wifi_full_info.py

Menampilkan:
 - interface Wi-Fi yang aktif (nama)
 - IP lokal dan MAC address interface
 - SSID dan BSSID (MAC access point)
 - Gateway
 - (opsional) ARP-scan / tabel ARP perangkat di LAN (IP + MAC)

Usage:
    python wifi_full_info.py [--scan] [--no-color]

Dependencies (recommended):
    pip install psutil netifaces

Optional (lebih lengkap scan ARP):
    pip install scapy

Notes:
 - ARP scan (active) biasanya butuh hak root/Administrator jika memakai scapy.
 - Fungsi SSID/BSSID memanggil utilitas sistem:
     - Linux: iwgetid / iw / nmcli
     - Windows: netsh
     - macOS: airport / networksetup
"""
import sys
import platform
import subprocess
import shlex
import socket
import argparse
import psutil
import netifaces
import re
from collections import namedtuple

# Optional import for active ARP scanning
try:
    from scapy.all import ARP, Ether, srp  # type: ignore
    SCAPY_AVAILABLE = True
except Exception:
    SCAPY_AVAILABLE = False

# Simple colored output
def color(text, c):
    return text if args.no_color else f"\033[{c}m{text}\033[0m"

def run(cmd, shell=False, timeout=6):
    """Run command and return stdout (text). On error return empty string."""
    try:
        if shell:
            out = subprocess.check_output(cmd, stderr=subprocess.DEVNULL, shell=True, text=True, timeout=timeout)
        else:
            out = subprocess.check_output(shlex.split(cmd), stderr=subprocess.DEVNULL, text=True, timeout=timeout)
        return out
    except Exception:
        return ""

def get_default_gateway():
    """Return (gateway_ip, interface) or (None, None)."""
    try:
        gws = netifaces.gateways()
        default = gws.get('default', {})
        if netifaces.AF_INET in default:
            gw_ip, iface = default[netifaces.AF_INET]
            return gw_ip, iface
    except Exception:
        pass
    return None, None

def list_interfaces_info():
    """Return dict of interface -> {'ip':..., 'mac':..., 'addrs': raw}"""
    out = {}
    for ifname, addrs in psutil.net_if_addrs().items():
        ip = None
        mac = None
        for a in addrs:
            # AF_INET for IPv4
            if getattr(a.family, 'name', '') == 'AF_INET' or a.family == socket.AF_INET:
                ip = a.address
            # MAC on many systems is AF_LINK or AF_PACKET
            if getattr(a.family, 'name', '') in ('AF_LINK', 'AF_PACKET') or a.family == psutil.AF_LINK:
                mac = a.address
        out[ifname] = {'ip': ip, 'mac': mac, 'addrs': addrs}
    return out

def detect_wifi_interface_linux():
    """Attempt to detect wireless interface on Linux."""
    # 1) try `iw dev` parse 'Interface <name>'
    out = run("iw dev", shell=True)
    if out:
        m = re.findall(r'Interface\s+(\S+)', out)
        if m:
            return m[0]
    # 2) try nmcli -t -f DEVICE,TYPE,STATE device | grep wifi.*connected
    out = run("nmcli -t -f DEVICE,TYPE,STATE device", shell=True)
    for line in out.splitlines():
        parts = line.split(":")
        if len(parts) >= 3 and parts[1] == "wifi" and parts[2] == "connected":
            return parts[0]
    # 3) fallback: choose first interface that looks like wlan or wl
    for ifname in list_interfaces_info().keys():
        if re.match(r'^(wlan|wl|wifi)', ifname, re.I):
            return ifname
    return None

def get_ssid_bssid_linux(iface_hint=None):
    """Return dict {'ssid':..., 'bssid':...}"""
    # try iwgetid
    ssid = run("iwgetid -r", shell=True).strip()
    bssid = ""
    iface = iface_hint
    if not iface:
        iface = detect_wifi_interface_linux()
    if iface:
        # try iw dev <iface> link
        out = run(f"iw dev {iface} link", shell=True)
        # look for 'Connected to <bssid>'
        m = re.search(r'Connected to\s+([0-9a-f:]{17})', out, re.I)
        if m:
            bssid = m.group(1)
        # sometimes 'SSID: <name>'
        m2 = re.search(r'SSID:\s*(.+)', out)
        if m2 and not ssid:
            ssid = m2.group(1).strip()
    # fallback to nmcli
    if (not ssid or not bssid) and run("which nmcli", shell=True):
        out = run("nmcli -t -f active,ssid,bssid dev wifi", shell=True)
        for line in out.splitlines():
            parts = line.split(":")
            if parts and parts[0] == 'yes':
                if not ssid:
                    ssid = parts[1]
                if not bssid and len(parts) > 2:
                    bssid = parts[2]
                break
    return {'ssid': ssid or None, 'bssid': bssid or None}

def get_ssid_bssid_windows():
    """Use netsh wlan show interfaces"""
    out = run("netsh wlan show interfaces", shell=True)
    ssid = None
    bssid = None
    for line in out.splitlines():
        line = line.strip()
        if line.lower().startswith("ssid"):
            # skip lines like "SSID name :"
            if ":" in line:
                try:
                    k, v = line.split(":", 1)
                    # some lines: "SSID : MyNetwork"
                    if k.strip().lower().startswith("ssid"):
                        ssid = v.strip()
                except Exception:
                    pass
        if line.lower().startswith("bssid"):
            if ":" in line:
                try:
                    _, v = line.split(":", 1)
                    bssid = v.strip()
                except Exception:
                    pass
    return {'ssid': ssid, 'bssid': bssid}

def get_ssid_bssid_macos():
    """Try airport utility or networksetup"""
    # airport -I
    airport_path = "/System/Library/PrivateFrameworks/Apple80211.framework/Versions/Current/Resources/airport"
    if run(f"test -x {airport_path} && echo ok", shell=True).strip():
        out = run(f"{airport_path} -I", shell=True)
        ssid = None
        bssid = None
        for line in out.splitlines():
            if "SSID:" in line:
                ssid = line.split("SSID:")[-1].strip()
            if "BSSID:" in line:
                bssid = line.split("BSSID:")[-1].strip()
        return {'ssid': ssid, 'bssid': bssid}
    # fallback networksetup to map hardware port
    out = run("networksetup -listallhardwareports", shell=True)
    iface = None
    for block in out.split("\n\n"):
        if "Wi-Fi" in block or "AirPort" in block:
            m = re.search(r'Device:\s*(\S+)', block)
            if m:
                iface = m.group(1)
                break
    if iface:
        out2 = run(f"ifconfig {iface}", shell=True)
        # try /System/Library/PrivateFrameworks/Apple80211... airport -I would be best
        ssid = None
        bssid = None
        # sometimes airport not present; leave None
        return {'ssid': ssid, 'bssid': bssid}
    return {'ssid': None, 'bssid': None}

def get_ssid_bssid(os_name, iface_hint=None):
    if os_name == 'linux':
        return get_ssid_bssid_linux(iface_hint)
    if os_name == 'windows':
        return get_ssid_bssid_windows()
    if os_name == 'darwin':
        return get_ssid_bssid_macos()
    return {'ssid': None, 'bssid': None}

def arp_table_from_proc():
    """Linux: parse /proc/net/arp"""
    table = []
    try:
        with open("/proc/net/arp", "r") as f:
            lines = f.read().splitlines()[1:]
            for l in lines:
                parts = l.split()
                if len(parts) >= 4:
                    ip = parts[0]
                    mac = parts[3]
                    if mac != "00:00:00:00:00:00":
                        table.append({'ip': ip, 'mac': mac})
    except Exception:
        pass
    return table

def arp_table_system():
    """Fallback: run `arp -a` and parse output (Windows/Mac/Linux)"""
    out = run("arp -a", shell=True)
    devices = []
    # Windows output: Interface: 192.168.1.5 --- 0x14
    #   Internet Address      Physical Address      Type
    #   192.168.1.1           xx-xx-xx-xx-xx-xx     dynamic
    # Mac/Linux: ? (192.168.1.10) at xx:xx:xx:xx:xx:xx [ether] on en0
    for line in out.splitlines():
        if not line.strip():
            continue
        # mac like xx:xx:xx:xx:xx:xx or xx-xx-xx-xx-xx-xx
        m = re.search(r'(\d{1,3}(?:\.\d{1,3}){3})[^\n\r]*?([0-9a-fA-F:-]{17}|[0-9A-Fa-f:-]{17})', line)
        if m:
            ip = m.group(1)
            mac = m.group(2).replace("-", ":").lower()
            devices.append({'ip': ip, 'mac': mac})
    return devices

def active_arp_scan(cidr=None, timeout=2):
    """Active ARP scan using scapy if available and root. Returns list of {'ip','mac'}."""
    devices = []
    if not SCAPY_AVAILABLE:
        print(color("[!] scapy not available — skipping active ARP scan", "33"))
        return devices
    # build default cidr if not provided
    if not cidr:
        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        try:
            s.connect(("8.8.8.8", 80))
            ip = s.getsockname()[0]
        finally:
            s.close()
        cidr = ip.rsplit('.',1)[0] + '.0/24'
    try:
        arp = ARP(pdst=cidr)
        ether = Ether(dst="ff:ff:ff:ff:ff:ff")
        packet = ether/arp
        answered, _ = srp(packet, timeout=timeout, verbose=0)
        for sent, received in answered:
            devices.append({'ip': received.psrc, 'mac': received.hwsrc})
    except PermissionError:
        print(color("[!] Active ARP scan needs root/Administrator privileges", "33"))
    except Exception as e:
        print(color(f"[!] Active ARP scan error: {e}", "31"))
    return devices

def get_wifi_info(os_name):
    # gather local interfaces info
    ifaces = list_interfaces_info()
    gw_ip, gw_iface = get_default_gateway()

    # pick wifi interface
    wifi_iface = None
    if os_name == 'linux':
        wifi_iface = detect_wifi_interface_linux() or gw_iface
    elif os_name == 'windows':
        # On Windows find interface from netsh show interfaces
        netsh = run("netsh wlan show interfaces", shell=True)
        m = re.search(r'Name\s*:\s*(.+)', netsh)
        if m:
            wifi_iface = m.group(1).strip()
        # fallback: choose interface that has IP and 'Wi-Fi' in psutil nic stats?
        if not wifi_iface:
            for name, info in ifaces.items():
                if info['ip'] and re.search(r'wifi|wi-?fi|wireless|wlan', name, re.I):
                    wifi_iface = name
    elif os_name == 'darwin':
        # macOS typically 'en0' or 'en1' — try to detect Wi-Fi via networksetup
        out = run("networksetup -listallhardwareports", shell=True)
        mblocks = re.split(r'\n\n+', out)
        wifi_dev = None
        for b in mblocks:
            if "Wi-Fi" in b or "AirPort" in b:
                m = re.search(r'Device:\s*(\S+)', b)
                if m:
                    wifi_dev = m.group(1)
                    break
        wifi_iface = wifi_dev or gw_iface
    else:
        wifi_iface = gw_iface

    # local ip/mac for chosen iface
    local_ip = None
    local_mac = None
    if wifi_iface and wifi_iface in ifaces:
        local_ip = ifaces[wifi_iface]['ip']
        local_mac = ifaces[wifi_iface]['mac']
    else:
        # fallback: choose first interface with an IPv4
        for name, info in ifaces.items():
            if info['ip']:
                local_ip = info['ip']
                local_mac = info['mac']
                wifi_iface = name
                break

    ssid_bssid = get_ssid_bssid(os_name, iface_hint=wifi_iface)

    return {
        'os': os_name,
        'iface': wifi_iface,
        'ip': local_ip,
        'mac': local_mac,
        'ssid': ssid_bssid.get('ssid'),
        'bssid': ssid_bssid.get('bssid'),
        'gateway': gw_ip
    }

def pretty_print(info):
    print(color("=== Wi-Fi / Network Info ===", "36"))
    print(f"OS           : {platform.system()} {platform.release()}")
    print(f"Interface    : {color(info['iface'] or 'N/A', '32')}")
    print(f"Local IP     : {color(info['ip'] or 'N/A', '32')}")
    print(f"Local MAC    : {color(info['mac'] or 'N/A', '32')}")
    print(f"Gateway      : {color(info['gateway'] or 'N/A', '32')}")
    print(f"SSID         : {color(info['ssid'] or 'N/A', '32')}")
    print(f"BSSID (AP)   : {color(info['bssid'] or 'N/A', '32')}")
    print()

def main(parsed_args):
    os_name = platform.system().lower()
    if os_name.startswith("linux"):
        os_key = 'linux'
    elif os_name.startswith("windows"):
        os_key = 'windows'
    elif os_name.startswith("darwin") or os_name.startswith("mac"):
        os_key = 'darwin'
    else:
        os_key = os_name

    info = get_wifi_info(os_key)
    pretty_print(info)

    if parsed_args.scan:
        print(color("=== ARP / LAN devices ===", "36"))
        devices = []
        # 1) try active scan if scapy available
        if SCAPY_AVAILABLE:
            print(color("[*] scapy available -> attempting active ARP scan (may need root)...", "34"))
            devices = active_arp_scan()
            if devices:
                for d in sorted(devices, key=lambda x: x['ip']):
                    print(f"{d['ip']:15}  {d['mac']}")
            else:
                print(color("[!] Active scan returned no results or was not permitted.", "33"))

        # 2) fallback to system ARP table (fast)
        if not devices:
            print(color("[*] Falling back to system ARP table (arp -a or /proc/net/arp)...", "34"))
            if platform.system().lower().startswith("linux"):
                devices = arp_table_from_proc()
            if not devices:
                devices = arp_table_system()
            if devices:
                # remove duplicates
                seen = set()
                cleaned = []
                for d in devices:
                    key = (d['ip'], d['mac'])
                    if key not in seen:
                        seen.add(key)
                        cleaned.append(d)
                for d in sorted(cleaned, key=lambda x: x['ip']):
                    print(f"{d['ip']:15}  {d['mac']}")
            else:
                print(color("[!] No ARP entries found.", "33"))

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Wi-Fi + LAN info (IP, MAC, SSID, BSSID) with optional ARP scan.")
    parser.add_argument("--scan", action="store_true", help="Perform ARP scan / show ARP table")
    parser.add_argument("--no-color", dest="no_color", action="store_true", help="Disable colored output")
    args = parser.parse_args()
    try:
        main(args)
    except KeyboardInterrupt:
        print("\nInterrupted by user")
    except Exception as e:
        print(f"Fatal error: {e}")
        raise
