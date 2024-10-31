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
      image: "./XII MIPA3/3.jpg",
    },
    {
      name: "Alviani Tri Tama",
      role: "",
      subject: "",
      image: "./XII MIPA3/4.jpg",
    },
    {
      name: "Annisa Nur Hanifah",
      role: "",
      subject: "",
      image: "./XII MIPA3/5.jpg",
    },
    {
      name: "Aura Citrawening Pujma Azzahra",
      role: "",
      subject: "",
      image: "./XII MIPA3/6.jpg",
    },
    {
      name: "Bagus Teguh Prakoso",
      role: "",
      subject: "",
      image: "./XII MIPA3/7.jpg",
    },
    {
      name: "Dewi Nurvikazahra",
      role: "",
      subject: "",
      image: "./XII MIPA3/8.jpg",
    },
    {
      name: "Diaz Ardian Syah",
      role: "",
      subject: "",
      image: "./XII MIPA3/9.jpg",
    },
    {
      name: "Eka Zahra Maulidia",
      role: "",
      subject: "",
      image: "./XII MIPA3/10.jpg",
    },
    {
      name: "Evania Atalie",
      role: "",
      subject: "",
      image: "./XII MIPA3/11.jpg",
    },
    {
      name: "Fandy Hakim Irwanto",
      role: "",
      subject: "",
      image: "./XII MIPA3/12.jpg",
    },
    {
      name: "Febyolla Friska Anggraini",
      role: "",
      subject: "",
      image: "./XII MIPA3/13.jpg",
    },
    {
      name: "Hanik Maftucha",
      role: "",
      subject: "",
      image: "./XII MIPA3/14.jpg",
    },
    {
      name: "Iko Sany Waldany",
      role: "",
      subject: "",
      image: "./XII MIPA3/15.jpg",
    },
    {
      name: "Johan Firdaus Muhammad Ikbal",
      role: "",
      subject: "",
      image: "./XII MIPA3/16.jpg",
    },
    {
      name: "Jovitha Try Novitha Engge",
      role: "",
      subject: "",
      image: "./XII MIPA3/17.jpg",
    },
    {
      name: "Lintang Ferdhi Firmansyah",
      role: "",
      subject: "",
      image: "./XII MIPA3/18.jpg",
    },
    {
      name: "M Mujtaba Fauzia",
      role: "",
      subject: "",
      image: "./XII MIPA3/19.jpg",
    },
    {
      name: "Maulidan Faizal Murano",
      role: "",
      subject: "",
      image: "./XII MIPA3/20.jpg",
    },
    {
      name: "M Dhimas Adjie Aryawiraradja",
      role: "",
      subject: "",
      image: "./XII MIPA3/21.jpg",
    },
    {
      name: "Nabil Patria Nayatama",
      role: "",
      subject: "",
      image: "./XII MIPA3/22.jpg",
    },
    {
      name: "Nabilla Zainatul Faizah",
      role: "",
      subject: "",
      image: "./XII MIPA3/23.jpg",
    },
    {
      name: "Najmi Huwaida Nur Faiza",
      role: "",
      subject: "",
      image: "./XII MIPA3/24.jpg",
    },
    {
      name: "Najwa Fadhilah",
      role: "",
      subject: "",
      image: "./XII MIPA3/25.jpg",
    },
    {
      name: "Nilia Izzatal Fitrah",
      role: "",
      subject: "",
      image: "./XII MIPA3/26.jpg",
    },
    {
      name: "Nindya Karenina",
      role: "",
      subject: "",
      image: "./XII MIPA3/27.jpg",
    },
    {
      name: "Reni Setyowati",
      role: "",
      subject: "",
      image: "./XII MIPA3/28.jpg",
    },
    {
      name: "Nur Rakhmawati",
      role: "",
      subject: "",
      image: "./XII MIPA3/29.jpg",
    },
    {
      name: "Rizky Shahleza Hanny Sulistyowati",
      role: "",
      subject: "",
      image: "./XII MIPA3/30.jpg",
    },
    {
      name: "Shinta Kartika Dewi",
      role: "",
      subject: "",
      image: "./XII MIPA3/31.jpg",
    },
    {
      name: "Silvia Diah Permatasari",
      role: "",
      subject: "",
      image: "./XII MIPA3/32.jpg",
    },
    {
      name: "Tangguh Firman Al-Fatchah",
      role: "",
      subject: "",
      image: "./XII MIPA3/33.jpg",
    },
    {
      name: "Vita Nur Amalina",
      role: "",
      subject: "",
      image: "./XII MIPA3/34.jpg",
    },
    {
      name: "Wahyu Eko Setyo Pribowo",
      role: "",
      subject: "",
      image: "./XII MIPA3/35.jpg",
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
