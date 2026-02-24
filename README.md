# Inventory Management System

Implements product entry, sale recording with VAT/discount/due, double-entry accounting journals, and date-wise financial report.

### Features Implemented

- Product creation (purchase/sell price + opening stock)  
- Sale recording (units, discount, 5% VAT, payment → auto-calculate due)  
- Automatic **double-entry journal entries** on every sale:  
  - Debit: Cash + Receivable  
  - Credit: Sales + VAT Payable  
  - Debit: COGS  
  - Credit: Inventory  
- Date-range financial report:  
  - Total sell / revenue  
  - Total expense (COGS)  
  - Net profit  
  - Date filter (from/to)

### Live Demo

**URL:** https://inventory-app.ar-techpro.com  

**Financial Report:** https://inventory-app.ar-techpro.com/reports/financial  

(No authentication required for demo)

### How to Test (Step-by-Step)

1. Visit https://inventory-app.ar-techpro.com/reports/financial  
2. See pre-seeded data:  
   - Product: 100 TK purchase, 200 TK sell, 50 units opening  
   - Sale: 10 units, 50 TK discount, 5% VAT, 1000 TK payment → Due 1047.50 TK  
   - Journals: 6 double-entry lines  
3. Change date range → see totals update

### Setup Instructions (Local)

1. `git clone https://github.com/mar-babu/inventory-app.git`
2. `composer install`  
3. Copy `.env.example` → `.env` and set DB credentials  
4. `php artisan key:generate`  
5. `php artisan migrate --seed`  
   (Seeds exact task data: product + sale + journals)  
6. `npm install && npm run dev`  
7. `php artisan serve`

### Seeded Data (exact task scenario)

**Product**  
Purchase Price: 100 TK  
Sell Price: 200 TK  
Opening Stock: 50 units  

**Sale**  
Sold: 10 units  
Discount: 50 TK  
VAT: 5%  
Payment: 1000 TK  
Due: 1047.50 TK  

**Journal Entries** (double-entry)  
- Cash debit 1000 + Receivable debit 1047.50  
- Sales credit 1950 + VAT Payable credit 97.50  
- COGS debit 1000 + Inventory credit 1000  

### Technical Notes

- Laravel 12 + Inertia.js (React) + Tailwind + SSR  
- Repository pattern + Service layer (InventoryService + AccountingService)  
- Double-entry logic fully automated in `AccountingService`  
- Report uses `sum()` with `whereBetween` on date  

**Deployed on Cpanel** – clean commit history maintained.

Screenshots included in `/screenshots/` folder.

**Ping me for any questions**
Email: ar_cse@yahoo.com
Mobile: +8801681195152