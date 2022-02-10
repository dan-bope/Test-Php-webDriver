### Start Server

````N.B : Use Chrome as your navigator.````  

### Starting the Server with Chromedriver

### Terminal 1 starting server

```` chromedriver --port=4444 ````

### Terminal 2 starting command phpunit

````.\vendor\bin\phpunit .\tests\iframeTest.php ````

- Function : ***testiframeTitre***
---

| url                             | function                | commande                                               | response                               |
| :----------------------------   | :----------------------: | :--------------------------------------------------:  | ------------------------------------:  |
| ```` block/comment/{id} ````    | ```` blockComment ````   |```` .\vendor\bin\phpunit .\tests\BlockTest.php  ````  | ```` OK (1 test, 3 assertions) ````    |

