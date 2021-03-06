# API

## Inhoudspogave

- [Gegevens opbouw in CiviCRM](#gegevens-opbouw-in-civicrm)
	* [Deelnemende teams](#deelnemende-teams)
	* [Deelnemers van een team](#deelnemers-van-een-team)
	* [Donaties, Collecte en Loterij](#donaties-collecte-en-loterij)
- [Toegangsrechten API](#toegangsrechten-api)
- [RoparunTeam.get - Lijstje met teams](#roparunteamget---lijstje-met-teams)
	* [Hoe te gebruiken?](#hoe-te-gebruiken)
- [RoparunTeam.gettotal - Totaalinformatie](#roparunteam.gettotal---totaalinformatie)
	* [Hoe te gebruiken?](#hoe-te-gebruiken-1)
- [RoparunTeam.getdetails - Detail overzicht van een team](#roparunteamgetdetails---detail-overzicht-van-een-team)
	* [Hoe te gebruiken?](#hoe-te-gebruiken-2)
- [RoparunTeam.getmembers - Overzicht van teamleden](#roparunteamgetmembers---overzicht-van-teamleden)
	* [Hoe te gebruiken?](#hoe-te-gebruiken-3)

Voor de integratie met de website zijn er 2 custom API's ontwikkeld.

* *RoparunTeam.get* haalt een lijstje met teams op
* *RoparunTeam.getdetails* haalt de details van team op; zoals deelnemers en wie er hoeveel gedoneerd heeft.

## Gegevens opbouw in CiviCRM

Deze API gaat er vanuit dat voor ieder evenement van het type _Roparun_ er ook een campagne is en het evenement aan een campagne gelinkt is.
Er wordt standaard van het eerst volgende Roparun evenement uit gegaan. Eventueel kun je aan de API een evenement ID meegeven.

### Deelnemende teams

Deelnemende teams worden gegevonden doordat ze als deelnemer met de rol _Team_ aan het Roparun evenement aangemeld staan.
Op de deelnemersregistratie staat ook het teamnummer en de teamnaam

### Deelnemers van een team

Deelnemers van een team staan geregistreed als deelnemer met de rol _Teamlid_ op het Roparun evenement. Op de registratie staat aan welk team ze deelnemen en met welke rol.

### Donaties, Collecte, Veiling en Loterij

Een donatie, collecte, veilingopbrengst en loterij zijn in CiviCRM als bijdrages vastgelegd met een apart financieel type. Bij de bijdrage geef je aan voor welk team het is en eventueel tbv van welk teamlid. 
De bijdrage is ook gekoppeld aan de campagne waar het evenement onder valt.  
Bij bijdrages van het type donatie wordt ook vastgelegd of de naam van de donateur vermeld mag worden of deze anoniem moet blijven. 

## Toegangsrechten API

Deze API heeft geen permissies of toegangsrechten nodig. Wel heb je een _site_key_ en _api_key_ nodig als je deze api over REST wil gebruiken.

## RoparunTeam.get - Lijstje met teams

Het lijstje met teams wordt bepaald aan de hand de deelnemers aan het eerst volgende Roparun evenement. Het eerste volgende roparun evenement duurt tot de datum ingevuld in het custom veld 'Doneren tot'

Per team geeft deze API de volgende gegevens terug:

* Teamnaam
* Teamnummer
* Startlocatie
* Vestigingsplaats
* Vestigingsland
* Facebook
* Twitter
* Website
* Instagram
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
            "start_location": "Hamburg",
            "city": "Ede",
            "country": "Netherlands",
            "website": "http://www.teamcivicoop-test.nl",
            "facebook": "https://facebook.com/teamcivicoop-test",
            "instagram": "https://www.instagram.com/teamcivicoop-test",
            "twitter": "https://twitter.com/teamcivicoop-test",
            "total_amount": "100"
    }
  ]
}
````

De ID's verwijzen naar contact ids in civicrm. En is nodig om de detail informatie van het team op te halen.

## RoparunTeam.gettotal - Totaalinformatie

Deze API geeft de totaalstand van de donaties terug. De totaalstand bestaat uit

* Totaal voor het hele evenement
* Totaal op naam van Roparun
* Totaal op naam van een team

De totaalstand gaat per Roparun Evenement. Eventueel kan je een Roparun Evenement doorgeven met _event_id_. Als die niet is opgegeven wordt het eerst volgende roparun evenement gebruikt. Het eerste volgende roparun evenement duurt tot de datum ingevuld in het custom veld 'Doneren tot'

### Hoe te gebruiken?

Een aaroep ziet er dan ongeveer zo uit:

````
http://roparun.local.com/sites/all/modules/civicrm/extern/rest.php?entity=RoparunTeam&action=getottal&api_key=userkey&key=sitekey
````

Resultaat ziet er zo uit:
````
{
    "total_amount": 320,
    "total_amount_teams": 310,
    "total_amount_roparun": 10,
    "is_error": "0",
    "version": 3
}
````

## RoparunTeam.getdetails - Detail overzicht van een team

Detail overzicht van een team geeft team informatie terug, de deelnemers, en alle donaties op naam van dit team.

### Parameters

Optionele parameters


| Parameter                           | Standaard waarde | Omschrijving                                                                                                              |
| ----------------------------------- | ---------------- | --------------------------------------------------------------------------------------------------------------------------|
| event_id                            |                  | standaard het meest recente roparun event                                                                                 |
| include_team_members_with_donations | 1                | Als deze op 1 staat worden ook deelnemers getoond die tonen op website is nee hebben staan maar wel donaties op hun naam. |


### Team informatie

De team informatie bestaat uit: 

* Teamnaam
* Teamnummer
* Startlocatie
* Vestigingsplaats
* Vestigingsland
* Facebook
* Twitter
* Website
* Instagram
* Contact ID van het team
* Totale stand van donaties op naam van dit team
* Totale stand van donaties op naam van alleen team (en niet een teamlid)
* Totale stand van donaties op naam van een teamlid
* Totale stand loten verkoop
* Totale stand sms donaties
* Totale stand collecte
* Totale stand veiling

### Teamleden

Van ieder teamlid wordt de volgende informatie teruggegeven:

* Naam
* Functie
* Woonplaats
* Totale stand donaties op naam van dit teamlid

### Donateurs

Van iedere donateur wordt de volgende informatie teruggegeven:

* Naam donateur, eventueel anoniem als de donateur dat heeft aangegeven
* Woonplaats donateur, leeg als donateur heeft aangegeven anoniem te willen doneren
* Tbv team lid, leeg als er alleen op naam van team is gedoneerd
* Bedrag

### Hoe te gebruiken?

Een aaroep ziet er dan ongeveer zo uit:

````
http://roparun.local.com/sites/all/modules/civicrm/extern/rest.php?entity=RoparunTeam&action=getdetails&team_id=25&api_key=userkey&key=sitekey
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
                "start_location": "Hamburg",
                "city": null,
                "country": null,
                "website": "http://www.teamcivicoop-test.nl",
                "facebook": "https://facebook.com/teamcivicoop-test",
                "instagram": "https://www.instagram.com/teamcivicoop-test",
                "twitter": "https://twitter.com/teamcivicoop-test",
                "total_amount": 1730,
                "total_amount_team": 10,
                "total_amount_team_members": 220,
                "total_amount_collecte": 1000,
                "total_amount_sms": 2.36,
                "total_amount_loterij": 500
                "total_amount_veiling": 150
            },
            "members": [
                {
                   "id": 27,
                    "name": "Sven Kramer",
                    "city": null,
                    "role": "Chaffeur",
                    "is_team_captain": 0,
                    "total_amount": 210
                },
                {
                		"id": 26,
                    "name": "Mark Tuitert",
                    "city": "Kamerik",
                    "role": "Teamcaptain",
                    "is_team_captain": 1
                    "total_amount": 10
                }
            ],
            "donations": [
                {
                    "donor": "Anoniem",
                    "team_member": "Mark Tuitert",
                    "city": "",
                    "amount": "10.00"
                },
                {
                    "donor": "jaap.jansma@civicoop.org",
                    "team_member": "",
                    "city": "Arnhem",
                    "amount": "10.00"
                },
                {
                    "donor": "jaap.jansma@civicoop.org",
                    "team_member": "Sven Kramer",
                    "city": "Ede",
                    "amount": "210.00"
                }
            ]
        }
    ]
}
````

## RoparunTeam.getmembers - Overzicht van teamleden

Overzicht van leden van een team. 

Van ieder teamlid wordt de volgende informatie teruggegeven:

* Naam
* Functie
* Is Team Captain: Ja/Nee
* Woonplaats

### Parameters

Optionele parameters

| Parameter                           | Standaard waarde | Omschrijving                                                                                                              |
+-------------------------------------+------------------+---------------------------------------------------------------------------------------------------------------------------+
| event_id                            |                  | standaard het meest recente roparun event                                                                                 |
| include_team_members_with_donations | 1                | Als deze op 1 staat worden ook deelnemers getoond die tonen op website is nee hebben staan maar wel donaties op hun naam. |

### Hoe te gebruiken?

Een aaroep ziet er dan ongeveer zo uit:

````
http://roparun.local.com/sites/all/modules/civicrm/extern/rest.php?entity=RoparunTeam&action=getmembers&team_id=25&api_key=userkey&key=sitekey
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
        	"id": 26,
            "name": "Sven Kramer",
            "city": null,
            "role": "Chaffeur",
            "is_team_captain": 0
        },
        {
        	"id": 27,
            "name": "Mark Tuitert",
            "city": "Kamerik",
            "role": "Loper",
            "is_team_captain": 1
        }
    ]
}
````


