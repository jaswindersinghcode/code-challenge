# Unit Testing Challenge

## Explaination

The class [`src/DoctorSlotsSynchronizer.php`](src/DoctorSlotsSynchronizer.php) does not exist in the same path anymore. 
The code is refactorized in symfony framework using Domain driven design.Inmemory repositories created for unit testing.

The doctrine repositories are created also in case of Integration tests.

There are the Api created for doctor and slots in routes.yaml.

## Installation
The project is dockerized and configured to work with `docker-compose`.

To run the container, use `make up` (install it using in case not installed: `sudo apt-get install make`)

In case the libraries are not updated use command `make composer-update`

To run the tests, run `make tests`

The app will be accessible on `http://localhost:2137`.

Enjoy!
