📄 Markerite haldusrakendus
1. Rakenduse kirjeldus

Tegemist on veebirakendusega, mis võimaldab kasutajal lisada, vaadata, muuta ja kustutada markereid kaardil. Kasutaja saab kaardil klõpsates valida asukoha ning lisada markerile nime ja kirjelduse. Kõik markerid salvestatakse andmebaasi ning kuvatakse kaardil.

2. Funktsionaalsus

Rakendus sisaldab järgmisi funktsionaalsusi:

Markeri lisamine kaardile klõpsates
Markerite kuvamine kaardil
Markeri muutmine (edit)
Markeri kustutamine
Markerite andmete salvestamine andmebaasi
Markerite andmete kuvamine popup aknas (nimi, kirjeldus, koordinaadid)
3. Kasutatud tehnoloogiad

Rakenduse arendamisel kasutati järgmisi tehnoloogiaid:

Laravel – backend ja loogika
Vue 3 – frontend
Inertia.js – ühendab Laraveli ja Vue
Leaflet – kaardi kuvamine
OpenStreetMap – kaardi andmed
MySQL – andmebaas
Tailwind CSS – kasutajaliidese kujundus
4. Rakenduse ülesehitus

Rakendus koosneb kahest põhiosast:

Backend (Laravel)
Vastutab andmete töötlemise ja salvestamise eest
Sisaldab markerite CRUD funktsioone:
lisamine (store)
muutmine (update)
kustutamine (destroy)
Kasutab andmebaasi tabelit markers
Frontend (Vue + Inertia)
Kuvab kaardi Leafleti abil
Kuvab kõik markerid kaardil
Võimaldab kasutajal lisada ja muuta markereid läbi vormi
Kasutab dialoogi akent markerite sisestamiseks

Andmed liiguvad backendi ja frontendi vahel Inertia.js abil.

5. Andmebaas

Rakenduses kasutatakse tabelit markers, mille väljad on:

id – primaarvõti
name – markeri nimi
lat – laiuskraad
lng – pikkuskraad
description – kirjeldus
added – lisamise aeg
edited – muutmise aeg
6. Rakenduse käivitamine

Projekti käivitamiseks:

Kloneeri projekt:
git clone <repo-link>
Liigu projekti kausta:
cd projekti-nimi
Paigalda sõltuvused:
composer install
npm install
Loo .env fail ja seadista andmebaas
Käivita migratsioonid:
php artisan migrate
Käivita arendusserver:
php artisan serve
npm run dev
Ava brauseris:
http://localhost:8000
7. Märkused

Rakendus kasutab Leaflet teeki kaardi kuvamiseks ning OpenStreetMap kaardiandmeid. Markerite lisamine toimub kasutaja kliki alusel ning kõik muudatused salvestatakse andmebaasi.

Rakendus on üles ehitatud lihtsa ja loogilise struktuuriga ning kasutab kaasaegseid veebiarenduse tehnoloogiaid.
