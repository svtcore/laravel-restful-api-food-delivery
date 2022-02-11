## Auth
URL |Method| Parameters | Description |
--- | --- |--- | --- |
login | POST | <ul><li>phone_country_code</li><li>phone_number</li><li>password</li><li>client_secret</li><li>client_id</li></ul>| Login with credentials|
register | POST | <ul><li>first_name</li><li>last_name</li><li>phone_country_code</li><li>phone_number</li><li>password</li></ul> | Create a new user account |

Required **Headers** for all request below (after authorization)
Method name|Value|
--- |--- |
Accept| application/json |
Authorization| Bearer YOUR_TOKEN_HERE |

## User role
### Restaurants
URL Prefix| URL |Method| Parameters | Description |
--- |--- | --- |--- | --- |
user| restaurants | GET | | Get list of available restaurants|
user| restaurants/{id} | GET | | Get restaurant data by ID|
user| restaurants/categories/{id} | GET | | Get restaurant data by category ID|
user| restaurants/cities/{id} | GET | | Get restaurant data by city ID|
user| restaurants/products/{id} | GET | | Get restaurant data by product ID|
user| restaurants/search | GET | key | Get restaurant data by key word|
user| restaurants/cities | GET || Get restaurant cities|

### Orders
URL Prefix| URL |Method| Parameters | Description |
--- |--- | --- |--- | --- |
user| orders | GET | | Get user orders|
user| orders/cities | GET | | Get list of available cities|
user| orders/cities/street-types/ | GET | | Get list of street types|
user| orders/{id} | GET | | Get user order by ID|
user| orders | POST | <ul><li>payment_type_id</li><li>discount_id</li><li>comment</li><li>city_id</li><li>street_type_id</li><li>street_name</li><li>building_number</li><li>entrace</li><li>floor</li><li>apartment</li><li>products (in json format {"product_id":quanity} ) </li></ul> | Create a new order |

### Products
URL Prefix| URL |Method| Parameters | Description |
--- |--- | --- |--- | --- |
user| products | GET | | Get list of products|
user| products/categories/ | GET | | Get list of categories|
user| products/{id} | GET | | Get product data by ID|
user| products/categories/1 | GET | | Get products by category ID|
user| products/restaurants/{id} | GET | available | Get products by restaurant ID |


### Payment methods
URL Prefix| URL |Method| Parameters | Description |
--- |--- | --- |--- | --- |
user| payment-methods | GET |available | Get list of payments data|
user| payment-methods/{id}| GET | | Get payment method data by ID|

## Admin

### Restaurants
URL Prefix| URL |Method| Parameters | Description |
--- |--- | --- |--- | --- |
admin| restaurants | GET | | Get list of available restaurants|
admin| restaurants | POST |<ul><li>name</li><li>working_time_start</li><li>working_time_end</li><li>working_day_start</li><li>working_day_end</li><li>city_id</li><li>street_type_id</li><li>street_name</li><li>building_number</li><li>description</li></ul> | Create a new restaurant data|
admin| restaurants/{id} | PUT |<ul><li>name</li><li>working_time_start</li><li>working_time_end</li><li>working_day_start</li><li>working_day_end</li><li>city_id</li><li>street_type_id</li><li>street_name</li><li>building_number</li><li>description</li></ul> | Update restaurant data by ID|
admin| restaurants/{id} | GET | | Get restaurant data by ID|
admin| restaurants/{id} | DELETE | | Delete restaurant data by ID|
admin| restaurants/{id}/delivery-types | GET | | Get delivery types data by restaurant ID|
admin| restaurants/{id}/addresses | GET | | Get list of restaurant addresses by ID|
admin| restaurants/{id}/addresses/{address_id} | GET | | Get address data by ID and restaurant ID|
admin| restaurants/{id}/addresses/ | POST | <ul><li>city_id</li><li>street_type_id</li><li>street_name</li><li>building_number</li></ul>| Add address to restaurant by ID|
admin| restaurants/{id}/addresses/{address_id} | PUT | <ul><li>city_id</li><li>street_type_id</li><li>street_name</li><li>building_number</li></ul>| Update address to restaurant by ID|
admin| restaurants/{id}/addresses/{address_id} | DELETE | | Delete address data by ID and restaurant ID|

### Delivery Types
URL Prefix| URL |Method| Parameters | Description |
--- |--- | --- |--- | --- |
admin| restaurants/{id}/delivery-types/{type_id} | GET | | Get delivery type data by restaurant ID and type ID |
admin| restaurants/{id}/delivery-types/{type_id} | DELETE | | Delete delivery type data by restaurant ID and type ID |
admin| restaurants/{id}/delivery-types | POST | <ul><li>name</li><li>price</li><li>available</li></ul>| Add delivery type by restaurant ID|
admin| /restaurants/{id}/delivery-types/{type_id} | POST | <ul><li>name</li><li>price</li><li>available</li></ul>| Update delivery type by restaurant ID and type ID|


### Orders
URL Prefix| URL |Method| Parameters | Description |
--- |--- | --- |--- | --- |
admin| restaurants/{id}/orders | GET | | Get orders by restaurant ID |
admin| restaurants/{id}/orders/statuses | GET | | Get available order statuses|
admin| restaurants/{id}/orders/{order_id} | GET | | Get order data by restaurant ID and order ID|
admin| restaurants/{id}/orders/{order_id}/status/ | PUT | status_id | Update order status by ID|
admin| restaurants/{id}/orders/statuses/{status_id} | GET | | Get orders by status ID|
admin| restaurants/{id}/orders/{order_id} | GET | | Delete order by ID|
admin| restaurants/{id}/orders | POST | <ul><li>payment_type_id</li><li>discount_id</li><li>comment</li><li>city_id</li><li>street_type_id</li><li>street_name</li><li>building_number</li><li>entrace</li><li>floor</li><li>apartment</li><li>products (in json format {"product_id":quanity} ) </li></ul> | Add new order |
admin| restaurants/{id}/orders/{order_id} | POST | <ul><li>payment_type_id</li><li>discount_id</li><li>comment</li><li>city_id</li><li>street_type_id</li><li>street_name</li><li>building_number</li><li>entrace</li><li>floor</li><li>apartment</li><li>products (in json format {"product_id":quanity} ) </li></ul> | Update order by ID |
