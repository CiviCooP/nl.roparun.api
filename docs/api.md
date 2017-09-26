# API

## Inhoudspogave

- [Gegevens opbouw in CiviCRM](#gegevens-opbouw-in-civicrm)
	* [Deelnemende teams](#deelnemende-teams)
	* [Deelnemers van een team](#deelnemers-van-een-team)
	* [Donaties, Collecte en Loterij](#donaties-collecte-en-loterij)
- [RoparunTeam.get - Lijstje met teams](#roparunteamget---lijstje-met-teams)
- [RoparunTeam.details - Detail overzicht van een team](#roparunteamdetails---detail-overzicht-van-een-team)

Voor de integratie met de website zijn er 2 custom API's ontwikkeld.

* *RoparunTeam.get* haalt een lijstje met teams op
* *RoparunTeam.details* haalt de details van team op; zoals deelnemers en wie er hoeveel gedoneerd heeft.

## Gegevens opbouw in CiviCRM

Deze API gaat er vanuit dat voor ieder evenement van het type _Roparun_ er ook een campagne is en het evenement aan een campagne gelinkt is.
Er wordt standaard van het eerst volgende Roparun evenement uit gegaan. Eventueel kun je aan de API een evenement ID meegeven.

### Deelnemende teams

Deelnemende teams worden gegevonden doordat ze als deelnemer met de rol _Team_ aan het Roparun evenement aangemeld staan.
Op de deelnemersregistratie staat ook het teamnummer en de teamnaam

### Deelnemers van een team

Deelnemers van een team staan geregistreed als deelnemer met de rol _Teamlid_ op het Roparun evenement. Op de registratie staat aan welk team ze deelnemen en met welke rol.

### Donaties, Collecte en Loterij

Een donatie, collecte en loterij zijn in CiviCRM als bijdrages vastgelegd met een apart financieel type. Bij de bijdrage geef je aan voor welk team het is en eventueel tbv van welk teamlid. 
De bijdrage is ook gekoppeld aan de campagne waar het evenement onder valt.  
Bij bijdrages van het type donatie wordt ook vastgelegd of de naam van de donateur vermeld mag worden of deze anoniem moet blijven. 

## RoparunTeam.get - Lijstje met teams

Het lijstje met teams wordt bepaald aan de hand de deelnemers aan het eerst volgende Roparun evenement. Het eerste volgende roparun evenement duurt tot de datum ingevuld in het custom veld 'Doneren tot'

Per team geeft deze API de volgende gegevens terug:

* Teamnaam
* Teamnummer
* Vestigingsplaats
* Vestigingsland
* Facebook
* Twitter
* Website
* Google Plus
* Instagram
* MySpace
* LinkedIn
* Pinterest
* Tumblr
* SnapChat
* Vine
* Contact ID van het team
* Totale stand van donaties op naam van dit team


*Optioneel*  kun je een event_id meegeven als je historische data wilt zien.

### Hoe te gebruiken?

Een aaroep ziet er dan ongeveer zo uit:

````
http://roparun.local.com/sites/all/modules/civicrm/extern/rest.php?entity=RoparunTeam&action=get&api_key=userkey&key=sitekey
````

Resultaat ziet er zo uit:

````
{
  "is_error": 0,
  "version": 3,
  "count": 1,
  "values":
  [
    {
            "id": "25",
            "name": "Team CiviCooP Test",
            "teamnr": "2",
            "city": "Ede",
            "country": "Netherlands",
            "website": "http://www.teamcivicoop-test.nl",
            "facebook": "https://facebook.com/teamcivicoop-test",
            "googleplus": "",
            "instagram": "https://www.instagram.com/teamcivicoop-test",
            "linkedin": "",
            "myspace": "",
            "pinterest": "",
            "snapchat": "",
            "tumblr": "",
            "twitter": "https://twitter.com/teamcivicoop-test",
            "vine": "",
            "total_amount": "100"
    }
  ]
}
````

De ID's verwijzen naar contact ids in civicrm. En is nodig om de detail informatie van het team op te halen.

## RoparunTeam.details - Detail overzicht van een team

Detail overzicht van een team geeft team informatie terug, de deelnemers, en alle donaties op naam van dit team.

*Optioneel* kun je een het event_id meegegevn als je historische gegevens wil hebben.

### Team informatie

De team informatie bestaat uit: 

* Teamnaam
* Teamnummer
* Vestigingsplaats
* Vestigingsland
* Facebook
* Twitter
* Website
* Google Plus
* Instagram
* MySpace
* LinkedIn
* Pinterest
* Tumblr
* SnapChat
* Vine
* Contact ID van het team
* Totale stand van donaties op naam van dit team
* Totale stand van donaties op naam van alleen team (en niet een teamlid)
* Totale stand van donaties op naam van een teamlid
* Totale stand loten verkoop
* Totale stand collecte

### Teamleden

Van ieder teamlid wordt de volgende informatie teruggegeven:

* Naam
* Functie
* Woonplaats
* Totale stand donaties op naam van dit teamlid

### Donateurs

Van iedere donateur wordt de volgende informatie teruggegeven:

* Naam donateur, eventueel anoniem als de donateur dat heeft aangegeven
* Tbv team lid, leeg als er alleen op naam van team is gedoneerd
* Bedrag

### Hoe te gebruiken?

Een aaroep ziet er dan ongeveer zo uit:

````
http://roparun.local.com/sites/all/modules/civicrm/extern/rest.php?entity=RoparunTeam&action=details&team_id=25&api_key=userkey&key=sitekey
````

Resultaat ziet er zo uit:
````
     {


    "is_error": 0,
    "version": 3,
    "count": 1,
    "id": 25,
    "values": [
        {
            "info": {
                "id": "25",
                "name": "Team CiviCooP Test",
                "teamnr": 2,
                "city": null,
                "country": null,
                "website": "http://www.teamcivicoop-test.nl",
                "facebook": "https://facebook.com/teamcivicoop-test",
                "googleplus": null,
                "instagram": "https://www.instagram.com/teamcivicoop-test",
                "linkedin": null,
                "myspace": null,
                "pinterest": null,
                "snapchat": null,
                "tumblr": null,
                "twitter": "https://twitter.com/teamcivicoop-test",
                "vine": null,
                "total_amount": 1730,
                "total_amount_team": 10,
                "total_amount_team_members": 220,
                "total_amount_collecte": 1000,
                "total_amount_loterij": 500
            },
            "members": [
                {
                    "name": "Sven Kramer",
                    "city": null,
                    "role": "Chaffeur",
                    "total_amount": 210
                },
                {
                    "name": "Mark Tuitert",
                    "city": "Kamerik",
                    "role": "Teamcaptain",
                    "total_amount": 10
                }
            ],
            "donations": [
                {
                    "donor": "Anoniem",
                    "team_member": "Mark Tuitert",
                    "amount": "10.00"
                },
                {
                    "donor": "jaap.jansma@civicoop.org",
                    "team_member": "",
                    "amount": "10.00"
                },
                {
                    "donor": "jaap.jansma@civicoop.org",
                    "team_member": "Sven Kramer",
                    "amount": "210.00"
                }
            ]
        }
    ]
}
````


