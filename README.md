# Jamir Barbershop Appointment Booking System

## Overview

Jamir Barbershop's Appointment Booking System is a web-based platform designed to modernize and automate the appointment management process for barbershops. The system enhances customer convenience, improves staff workflow, and provides centralized management for administrators across multiple branches.

This study focuses on implementing automation and recommendation-based features to improve existing appointment systems. The platform aims to deliver a more efficient booking experience while also improving overall user interaction and usability.

---

# Key Features

## Multiple Interfaces

The system provides separate interfaces for:

* Customers
* Staff/Barbers
* Administrators

Each interface is designed to support the specific roles, permissions, and responsibilities of its users.

---

## Appointment Booking

Customers can independently book appointments without requiring staff assistance. The booking process includes:

* Branch selection
* Service selection
* Barber selection
* Schedule selection

The system automatically adjusts appointment duration depending on the number of selected services.

---

## Automated Notifications

The system automatically sends notifications through:

* Email
* SMS

Features include:

* Appointment reminders
* Appointment confirmations
* Staff schedule notifications
* Email verification links
* SMS OTP verification for phone registrations

---

## AI-Based Haircut Recommendations

The platform provides personalized haircut recommendations using Artificial Intelligence.

How it works:

1. Users upload a facial photo
2. The system analyzes the user’s face shape
3. Suitable haircut styles are recommended automatically

This feature helps customers discover hairstyles that best match their facial structure.

---

## Barber Recommendations

During the appointment process, the system automatically recommends barbers based on the selected haircut or service specialization.

Customers may still freely choose other barbers if preferred.

---

## Customer Reviews and Ratings

Customers can submit reviews after completing appointments.

Features include:

* 1–5 star rating system
* Optional written feedback
* Anonymous review option

This helps improve service quality and customer satisfaction.

---

## Loyalty Program

The system includes a loyalty rewards feature where customers can:

* Earn loyalty progress after completed appointments
* Receive discounts and promotions
* Track loyalty card progress

Both staff and administrators can monitor customer loyalty information.

---

## Home Advertisements Management

The website homepage displays:

* Current promotions
* Discounts
* Featured services
* Announcements

Administrators can easily manage and update advertisements through the admin panel.

---

## Barber Calendar

The system provides scheduling calendars for:

* Customers (public barber availability)
* Staff (personal schedules)
* Administrators (all branch schedules)

This improves scheduling transparency and appointment management.

---

## Centralized Branch Management

The system uses a centralized database architecture that allows administrators to manage multiple branches within a single platform.

The admin dashboard includes:

* Analytics and reports
* Appointment schedules
* Customer records
* Barber information
* Branch monitoring

---

# Objectives

The system aims to:

* Improve appointment scheduling efficiency
* Reduce manual booking processes
* Enhance customer experience
* Provide intelligent recommendations
* Simplify branch management
* Increase communication through automated notifications
* Evaluate overall system usability through user feedback

---

# Technologies Used

Possible technologies used in the system include:

* Laravel Framework
* PHP
* MySQL
* Livewire
* Filament Admin Panel
* JavaScript
* Tailwind CSS
* AI-based facial analysis tools
* SMS and Email API integrations

---

# Target Users

The system is intended for:

* Barbershop Customers
* Barbers/Staff
* Barbershop Owners and Administrators

---

# Conclusion

The Barbershop Appointment Booking System provides a modern and intelligent solution for managing barbershop operations. By combining automation, AI-powered recommendations, centralized management, and customer-focused features, the system improves efficiency, enhances user experience, and supports better service delivery across multiple branches.

# System Requirements 

Before running the project, make sure you have the following installed:

* PHP >= 8.x
* Composer
* MySQL
* Laravel
* Node.js & NPM
* Git
* XAMPP / Laragon / WAMP

# Installation Guide

1. Clone the Repository or Download as Zip File
       For cloning: git clone https://github.com/kurt-02/Jamir-Barbershop.git
       Extract the Zip file and open on your text editor
2. Install PHP Dependencies
       composer install
3. Install Node Dependencies
       npm install
4. Configure Environment File
    Copy the example environment file:
        cp env.example env
    Update the database configuration inside the .env file:
        DB_DATABASE=your_database_name
        DB_USERNAME=your_username
        DB_PASSWORD=your_password
5. Create a Symbolic Link
        php artisan storage:link
6. Compile Frontend Assets
       npm run dev
8. Run the Development Server
       php artisan serve
