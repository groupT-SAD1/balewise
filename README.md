# BaleWise

**Know the bale before you buy.**

BaleWise is a web app that helps second-hand clothing traders estimate the value of a sealed bale before paying for it. The trader samples a few items, counts them by grade (A, B, C or Reject), and enters the bale's weight and the supplier's asking price. The app returns a value range and a verdict: good buy, fair or risky.

After a bale is opened, the trader records its real value. The app uses these results to learn how reliable each supplier's bales are, so future estimates improve.

## Features

- Value a bale from sample grade counts, weight and asking price
- Supplier reliability factor, learned from opened bales
- Bale history and per-supplier reliability view
- Editable grade prices
- Mobile-friendly web interface

## How the estimate works

```
value range = sample mix × grade prices × bale weight × supplier factor
```

A supplier with no history gets a wide range (plus or minus 15 percent). A consistent supplier gets a narrower range.

## Technology

- PHP
- MySQL / MariaDB
- HTML, CSS and JavaScript
- Runs on XAMPP (Apache, PHP and MySQL)

## Project structure

| File or folder | Purpose | In this repository |
| --- | --- | --- |
| `index.php` | Main entry page | Yes |
| `valuation.php` | Calculates the value range and verdict | Yes |
| `bale.php` | Bale record view and actual-value entry | Yes |
| `history.php` | Bale history and supplier reliability | Yes |
| `prices.php` | Grade price settings | Yes |
| `db.php` | Database connection | Yes |
| `layout.php` | Shared page layout | Yes |
| `config.sample.php` | Template for the database settings | Yes |
| `config.php` | Your database settings, including the password | No (ignored by `.gitignore`) |
| `sql/` | Database structure export | Yes |
| `assets/` | Stylesheet and icon | Yes |
| `manifest.json` | Web app manifest | Yes |
| `sw.js` | Service worker | Yes |
| `uploads/` | Sample photos saved by the app | No (created automatically) |
| `.gitignore` | Lists files Git should not track | Yes |

## Setup

### Requirements

- XAMPP (or any PHP and MySQL/MariaDB environment)
- A web browser

### Steps

1. Copy the project folder to `C:\xampp\htdocs\balewise`.
2. Start **Apache** and **MySQL** in the XAMPP control panel.
3. Create the database. Open http://localhost/phpmyadmin, click **New**, and create a database named `balewise`.
4. Import the database structure. In phpMyAdmin, select the `balewise` database, click **Import**, and choose the file in the `sql/` folder.
5. Create your configuration file. Copy `config.sample.php` to `config.php` in the same folder, then set your database user and password in `config.php`.
6. Make sure the `uploads/` folder exists and can be written to. It stores sample photos.
7. Open http://localhost/balewise in your browser.

> **Important:** Do not commit `config.php`. It holds your database password and is listed in `.gitignore`.

## Notes

- Grades are counted by hand. The sample photo is stored with the bale record, but it is not analysed automatically.
- The demo figures in the project documentation come from demo data, not real market prices.

## Authors

Group T

| Full name | Registration number | Role |
| --- | --- | --- |
| Ssamba Kenneth | 2025-08-43772 | Leader |
| Akankwasa Annet | 2025-08-42857 | Member |
| Auma Mary Goretty | 2025-08-40434 | Member |
| Hasahya Sheilla E | 2025-08-40179 | Member |
| Kengonzi Tracy | 2025-08-43434 | Member |
| Maganda Paffeous | 2025-08-44764 | Member |
| Nakavuma Julian | 2025-08-41677 | Member |
| Nakayiira Bridget | 2025-08-41277 | Member |
| Nankabirwa Janaat | 2025-08-40168 | Member |
| Nakimuli Tracy | 2025-08-41648 | Member |
## Licence

This project was produced for academic coursework.
