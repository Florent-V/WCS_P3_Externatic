
<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>

- [Externatic](#externatic)
    - [🏃 Clone and run Externatic](#-clone-and-run-externatic)
        - [Prerequisites](#prerequisites)
        - [Install](#install)
        - [Launching](#launching)
    - [🏠 Architecture of Externatic](#-architecture-of-externatic)
        - [Organisation](#organisation)
        - [Public pages](#public-pages)
        - [Page when login as Candidate](#page-when-login-as-candidate)
        - [Consultant page](#consultant-page)
        - [Admin pages](#admin-pages)
    - [🔑 Login to Externatic](#-login-to-externatic)
        - [Admin](#admin)
        - [Consultant](#consultant)
        - [Candidate](#candidate)
    - [📰 Info about Externatic](#-info-about-externatic)
        - [Our team](#our-team)
        - [Built With](#built-with)
    - [🌏 Browser Support](#-browser-support)

</details>


# Externatic

<a name="readme-top"></a>

![Externatic](https://i.imgur.com/zMuSY2p.jpg)

## 🏃 Clone and run Externatic

### Prerequisites

1. Check composer is installed
2. Check yarn & node are installed

### Install

1. Clone this project
2. Create your .env.local
3. Add & personalize this line to your .env.local :

DATABASE_URL="mysql://user:password@127.0.0.1:3306/dbname?serverVersion=8.0&charset=utf8mb4"

4. For testing this project, you have to set a mailer in your .env.local like this :

###> symfony/mailer ###
MAILER_DSN=smtp://...
MAILER_FROM_ADDRESS=your@mail.fr
###< symfony/mailer ###

5. Run `bash script.sh`
6. Script.sh What ? Here what this script do:

    * #### Installer le projet
    1. Run `composer install`
    2. Run `yarn install`

    * #### Create the database
    3. Run `php bin/console d:d:c`
    4. Run `php bin/console d:m:m`
    5. Run `php bin/console d:f:l`


### Launching

1. Run `symfony server:start` to launch your local php web server
2. Run `yarn run dev --watch` to launch your local server for assets (or `yarn dev-server` do the same with Hot Module Reload activated)

<p align="right">(<a href="#readme-top">back to top</a>)</p>

## 🏠 Architecture of Externatic

### Organisation
There is **3 access level** to externatic :

* **Candidate :** He can create his candidate profile, read job offer and apply to any of them
* **Consultant :** He can create job offer and validate candidacy
* **Admin :** He can access any CRUD

### Public pages
* Home page at [localhost:8000/](http://localhost:8000/)
* Connect at [localhost:8000/login](http://localhost:8000/login)
* Register at [localhost:8000/register](http://localhost:8000/register)

### Page when login as Candidate
* Get the profile page at [localhost:8000/profile](http://localhost:8000/candidat/mypage)
* Use the Hamburger menu to access other links

### Consultant page
* Get the profile page at [localhost:8000/consultant/board](http://localhost:8000/consultant/board)
* Use the Hamburger menu to access other links

### Admin pages
* Adminb is a Super-Consultant so he has the same features than the consultant and Admin features so :
* Use the Hamburger menu to access other features

<p align="right">(<a href="#readme-top">back to top</a>)</p>

## 🔑 Login to Externatic

### Admin

* email : `admin@mail.fr` // password : `motdepasse`


### Consultant
From
* email : `consultant1@mail.fr` // password : `motdepasse`


To
* email : `consultant3@mail.fr` // password : `motdepasse`



### Candidate

From
* email : `candidat1@mail.fr` // password : `motdepasse`

To
* email : `candidat50@mail.fr` // password : `motdepasse`

<p align="right">(<a href="#readme-top">back to top</a>)</p>


## 📰 Info about Externatic

### Our team

Externatic is a [school](https://www.wildcodeschool.com/) project created by :

* Emeric Riff  [<img src="https://i.imgur.com/i3QdWQl.png">](https://www.linkedin.com/in/e-riff/)    [<img src="https://i.imgur.com/MXFQZTy.png">](https://github.com/e-riff)


* Maxence Hattabi  [<img src="https://i.imgur.com/i3QdWQl.png">](https://www.linkedin.com/in/maxence-hattabi-75033617b/)    [<img src="https://i.imgur.com/MXFQZTy.png">](https://github.com/MaxenceCodeFR)


* Nicolas Montard  [<img src="https://i.imgur.com/i3QdWQl.png">](https://www.linkedin.com/in/nicolas-montard-9ba08a258/)    [<img src="https://i.imgur.com/MXFQZTy.png">](https://github.com/Nicolas-Montard)


* Florent Vasseur  [<img src="https://i.imgur.com/i3QdWQl.png">](https://www.linkedin.com/in/florentvasseur/)    [<img src="https://i.imgur.com/MXFQZTy.png">](https://github.com/Florent-V)




### Built With

* [Symfony](https://github.com/symfony/symfony)
* [Bootstrap](https://getbootstrap.com/)
* [SymfonyUX](https://ux.symfony.com/)

<p align="right">(<a href="#readme-top">back to top</a>)</p>


## 🌏 Browser Support

| <img src="https://user-images.githubusercontent.com/1215767/34348387-a2e64588-ea4d-11e7-8267-a43365103afe.png" alt="Chrome" width="16px" height="16px" /> Chrome | <img src="https://user-images.githubusercontent.com/1215767/34348590-250b3ca2-ea4f-11e7-9efb-da953359321f.png" alt="IE" width="16px" height="16px" /> Internet Explorer | <img src="https://user-images.githubusercontent.com/1215767/34348380-93e77ae8-ea4d-11e7-8696-9a989ddbbbf5.png" alt="Edge" width="16px" height="16px" /> Edge | <img src="https://user-images.githubusercontent.com/1215767/34348394-a981f892-ea4d-11e7-9156-d128d58386b9.png" alt="Safari" width="16px" height="16px" /> Safari | <img src="https://user-images.githubusercontent.com/1215767/34348383-9e7ed492-ea4d-11e7-910c-03b39d52f496.png" alt="Firefox" width="16px" height="16px" /> Firefox |
| :---------: | :---------: | :---------: | :---------: | :---------: |
| Yes | 11+ | Yes | Yes | Yes |

<p align="right">(<a href="#readme-top">back to top</a>)</p>


