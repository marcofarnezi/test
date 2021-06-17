# Assignment
## _v1.0.0_

We’d like you to create a small e-commerce application for us to get some insights in your skillset. This shouldn’t take more than two hours and it doesn’t have to be finished nor perfect.

# Installation
- `git clone git@github.com:Orderchamp/assignment.git`
- `composer install`
- `npm install && npm run dev`
- `php artisan serve`

# Description
Our users should be able to add products that are in stock to their shopping cart. During checkout, our visitors should be able to become users and our users should be able to review their previously stored information (name, address, contact details).

Fifteen minutes after checkout, a user should receive a discount code of € 5,- for future purchases. If a user chooses to use a discount code, you should keep track of what discount code was applied and what amount was subtracted from the checkout.

# Out-of-scope
The UI doesn’t have to be pretty. Payments in this application are based on invoices. Invoices are out of scope :-)

#Command to create data
- php artisan make:product
- php artisan make:stock
- php artisan make:coupon
- php artisan make:campaign
