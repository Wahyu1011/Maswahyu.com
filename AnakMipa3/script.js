document.addEventListener("DOMContentLoaded", function () {
  const staffData = [
    {
      name: "Henny Atriani, S.Pd., M.Si",
      role: "",
      subject: "",
      image: "./XII MIPA3/0.webp",
    },
    {
      name: "Afifur Rahman",
      role: "",
      subject: "",
      image: "./XII MIPA3/1.JPG",
    },
    {
      name: "Afina Hafiyyan Nisa'",
      role: "",
      subject: "",
      image: "./XII MIPA3/2.JPG",
    },
    {
      name: "Aini Dwi Mesyana",
      role: "",
      subject: "",
      image: "./XII MIPA3/3.JPG",
    },
    {
      name: "Alviani Tri Tama",
      role: "",
      subject: "",
      image: "./XII MIPA3/4.JPG",
    },
    {
      name: "Annisa Nur Hanifah",
      role: "",
      subject: "",
      image: "./XII MIPA3/5.JPG",
    },
    {
      name: "Aura Citrawening Pujma Azzahra",
      role: "",
      subject: "",
      image: "./XII MIPA3/6.JPG",
    },
    {
      name: "Bagus Teguh Prakoso",
      role: "",
      subject: "",
      image: "./XII MIPA3/7.JPG",
    },
    {
      name: "Dewi Nurvikazahra",
      role: "",
      subject: "",
      image: "./XII MIPA3/8.JPG",
    },
    {
      name: "Diaz Ardian Syah",
      role: "",
      subject: "",
      image: "./XII MIPA3/9.JPG",
    },
    {
      name: "Eka Zahra Maulidia",
      role: "",
      subject: "",
      image: "./XII MIPA3/10.JPG",
    },
    {
      name: "Evania Atalie",
      role: "",
      subject: "",
      image: "./XII MIPA3/11.JPG",
    },
    {
      name: "Fandy Hakim Irwanto",
      role: "",
      subject: "",
      image: "./XII MIPA3/12.JPG",
    },
    {
      name: "Febyolla Friska Anggraini",
      role: "",
      subject: "",
      image: "./XII MIPA3/13.JPG",
    },
    {
      name: "Hanik Maftucha",
      role: "",
      subject: "",
      image: "./XII MIPA3/14.JPG",
    },
    {
      name: "Iko Sany Waldany",
      role: "",
      subject: "",
      image: "./XII MIPA3/15.JPG",
    },
    {
      name: "Johan Firdaus Muhammad Ikbal",
      role: "",
      subject: "",
      image: "./XII MIPA3/16.JPG",
    },
    {
      name: "Jovitha Try Novitha Engge",
      role: "",
      subject: "",
      image: "./XII MIPA3/17.JPG",
    },
    {
      name: "Lintang Ferdhi Firmansyah",
      role: "",
      subject: "",
      image: "./XII MIPA3/18.JPG",
    },
    {
      name: "M Mujtaba Fauzia",
      role: "",
      subject: "",
      image: "./XII MIPA3/19.JPG",
    },
    {
      name: "Maulidan Faizal Murano",
      role: "",
      subject: "",
      image: "./XII MIPA3/20.JPG",
    },
    {
      name: "M Dhimas Adjie Aryawiraradja",
      role: "",
      subject: "",
      image: "./XII MIPA3/21.JPG",
    },
    {
      name: "Nabil Patria Nayatama",
      role: "",
      subject: "",
      image: "./XII MIPA3/22.JPG",
    },
    {
      name: "Nabilla Zainatul Faizah",
      role: "",
      subject: "",
      image: "./XII MIPA3/23.JPG",
    },
    {
      name: "Najmi Huwaida Nur Faiza",
      role: "",
      subject: "",
      image: "./XII MIPA3/24.JPG",
    },
    {
      name: "Najwa Fadhilah",
      role: "",
      subject: "",
      image: "./XII MIPA3/25.JPG",
    },
    {
      name: "Nilia Izzatal Fitrah",
      role: "",
      subject: "",
      image: "./XII MIPA3/26.JPG",
    },
    {
      name: "Nindya Karenina",
      role: "",
      subject: "",
      image: "./XII MIPA3/27.JPG",
    },
    {
      name: "Reni Setyowati",
      role: "",
      subject: "",
      image: "./XII MIPA3/28.JPG",
    },
    {
      name: "Nur Rakhmawati",
      role: "",
      subject: "",
      image: "./XII MIPA3/29.JPG",
    },
    {
      name: "Rizky Shahleza Hanny Sulistyowati",
      role: "",
      subject: "",
      image: "./XII MIPA3/30.JPG",
    },
    {
      name: "Shinta Kartika Dewi",
      role: "",
      subject: "",
      image: "./XII MIPA3/31.JPG",
    },
    {
      name: "Silvia Diah Permatasari",
      role: "",
      subject: "",
      image: "./XII MIPA3/32.JPG",
    },
    {
      name: "Tangguh Firman Al-Fatchah",
      role: "",
      subject: "",
      image: "./XII MIPA3/33.JPG",
    },
    {
      name: "Vita Nur Amalina",
      role: "",
      subject: "",
      image: "./XII MIPA3/34.JPG",
    },
    {
      name: "Wahyu Eko Setyo Pribowo",
      role: "",
      subject: "",
      image: "./XII MIPA3/35.JPG",
    },

    // Add more staff members here
  ];

  const staffGrid = document.querySelector(".staff-grid");

  staffData.forEach((staff) => {
    const staffMember = document.createElement("div");
    staffMember.classList.add("staff-member");
    staffMember.innerHTML = `
            <img src="${staff.image}" alt="${staff.name}">
            <h3>${staff.name}</h3>
            <p>${staff.role}</p>
            ${staff.subject ? `<p>${staff.subject}</p>` : ""}
        `;
    staffGrid.appendChild(staffMember);
  });
});
