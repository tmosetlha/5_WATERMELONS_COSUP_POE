# COSUP — Website & Mobile Application

> A dual-platform digital solution for the **Community Oriented Substance Use Programme (COSUP)** — a harm-reduction public health initiative serving 5,000+ individuals across 16 sites in Tshwane, South Africa.

Built by **The 5 Watermelons** for the **XISD5319 — Work Integrated Learning** module at **IIE Rosebank International, Pretoria CBD**.

![Status](https://img.shields.io/badge/status-final%20submission-brightgreen)
![Module](https://img.shields.io/badge/module-XISD5319-blue)
![License](https://img.shields.io/badge/license-academic%20use-lightgrey)

---

## 🔗 Live Links

| Component | Link |
|---|---|
| 🌐 Website V1 (Live) | [tmosetlha.github.io/COSUP_WEBSITE_VERSION_1](https://tmosetlha.github.io/COSUP_WEBSITE_VERSION_1/) |
| 🎨 UI/UX Prototype | [tmosetlha.github.io/cosup-prototype](https://tmosetlha.github.io/cosup-prototype/) |
| 📱 Android APK | Available via GitHub Releases — `cosupv2` repository (`com.example.cosupv2`) |

---

## 📖 About

COSUP has operated for over a decade as a partnership between the **City of Tshwane** and the **University of Pretoria's Department of Family Medicine**, without any dedicated digital presence. This project delivers a **website** and **Android mobile app** providing an accessible, confidential, multilingual gateway to COSUP's services — fully compliant with **POPIA (Act 4 of 2013)**. No sensitive health data is stored on either platform; the system functions strictly as a public-facing information, navigation, and communication tool.

## ✨ Key Features

- 🏥 **Clinic Locator** — interactive map of all 16 COSUP sites with address, hours, and contact details
- 🌍 **Multilingual Support** — English, IsiZulu, Xitsonga, TshiVenda, SeTswana/Sepedi, Afrikaans, Xhosa
- 🆘 **Hopeline SOS** — one-tap call/WhatsApp button (0800 611 197), always visible
- 📝 **Self-Referral Form** — anonymous-friendly, POPIA-compliant consent
- 🔐 **Role-Based Admin Portal** — JWT-protected, manage clinic data, news, and referrals
- 📰 **News & Updates Feed**
- 🌓 **Dark / Light Mode** — dark by default, preference persists per session
- 📊 **Reporting** — PDF/CSV exports for referrals, site listings, and Hopeline usage

## 🛠 Tech Stack

| Layer | Technology |
|---|---|
| **Frontend (Website)** | HTML5, CSS3, JavaScript — DM Sans & Playfair Display fonts |
| **Frontend (Mobile)** | Kotlin, Android Studio, OSMDroid (OpenStreetMap) |
| **Backend** | ASP.NET MVC, C#, Visual Studio 2022 — REST API |
| **Database** | Microsoft SQL Server (Developer Edition) — 9 tables, 3NF |
| **Auth** | JWT + BCrypt password hashing |
| **Email** | Mailgun / SendGrid |
| **Maps** | Google Maps JavaScript API (web), OSMDroid (mobile) |
| **Hosting** | GitHub Pages (Website V1), CloudLabs academic environment (full stack) |

## 🗄 Database Schema

Nine tables in Third Normal Form: `User`, `Session`, `Site`, `SiteService`, `Service`, `Referral`, `NewsItem`, `HopelineLog`, `AuditLog`.

## 🏗 Architecture

Three-tier client-server architecture:

1. **Presentation Layer** — Website (HTML/CSS/JS) + Android app (Kotlin)
2. **Application/Business Logic Layer** — ASP.NET MVC REST API
3. **Data Persistence Layer** — Microsoft SQL Server

## 👥 Team — The 5 Watermelons

| Name | Role |
|---|---|
| Zandile Selao | Project Manager |
| Tshiamo Mosetlha ([@tmosetlha](https://github.com/tmosetlha)) | Lead Developer |
| Matlhogonolo Keebine | UI/UX Designer |
| Muhluri Nkuna | Business Analyst |
| Ndabezinhle Mathunyane | Developer / QA Tester |

## ✅ Compliance

Built in full compliance with the **Protection of Personal Information Act (POPIA, Act 4 of 2013)**. Consent is required on all data capture forms, no sensitive health data is stored, and users retain rights to access, correct, or request deletion of their data.

## 📜 License

This project was developed for academic purposes as part of the IIE Rosebank International WIL module, in partnership with COSUP. Not licensed for commercial redistribution without permission from COSUP.

---

<p align="center"><i>"Recovery is a community journey."</i><br>COSUP Hopeline: <b>0800 611 197</b></p>
