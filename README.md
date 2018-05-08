# Custom Roparun API

This extensions provides two custom API's for Roparun:
* _RoparunTeam.get_ Gets a list with all the teams
* _RoparunTeam.getdetails_ Gets a detailed overview of an individual team.
* _RoparunTeam.getmembers_ Gets a lits with the teammembers
* _RoparunTeam.gettotal_ Gets a list with total amount donated.

The API is used for the following parts in the system

* **Website**: the website uses the _RoparunTeam.get_, _RoparunTeam.getDetails_ and _RoparunTeam.gettotal_
* **Donation form**: _RoparunTeam.get_, _RoparunTeam.getmembers_

See [docs in Dutch](docs/api.md)
