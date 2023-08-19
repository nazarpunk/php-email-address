Многие разработчики испытывают трудности с валидацией [email](https://en.wikipedia.org/wiki/Comparison_of_webmail_providers).
Одни просто используют [RegExp](http://www.ex-parrot.com/~pdw/Mail-RFC822-Address.html), другие [отказываются](https://habr.com/ru/articles/175375) от проверок, третьи рисуют [таблицы](https://developer.roman.grinyov.name/blog/92).

# Немного философии

Электронная почта прошла большой путь, который оставил на ней свои следы. Но зачастую, поддерживать весь функционал электронных адресов не требуется.
В большинстве своём, пользователи пользуются почтовыми сервисами. Посему здравой идеей будет плясать от них.

Поэтому проверка будет очень проста:

- очищаем строку от пробело
- разбиваем по @ на две части
- по доменной части проверяем оставшуюся строку
- если поставщик не найдек, поступаем по вкусу

# Что может пойти не так?

## Точка

У некоторых почтовых сервисов, есть интересная особенность - не значащие точки. Тобишь `example@gmail.com` и `e.x.a.m.p.l.e@gmail.com` это один и тот же адрес.
Соотвественно, если пользователь зарегестрирует несколько аккаунтов, а потом решить привязать `oauth`, то может быть очень весело.

## Плюс

У некоторых почтовых сервисов, есть интересная особенность - игнорировать всё после знака `+`. Тобишь `example@gmail.com` и `example+spam@gmail.com` это один и тот же адрес.
Соотвественно, если пользователь зарегестрирует несколько аккаунтов, а потом решить привязать `oauth`, то может быть очень весело.

## Домены

Некоторые почтовые сервисы, игрнорирую разницу в доменах. Тобишь `example@gmail.com` и `example@googlemail.com` это один и тот же адрес.
Соотвественно, если пользователь зарегестрирует несколько аккаунтов, а потом решить привязать `oauth`, то может быть очень весело.

## Очень умные пользователи

Поднять свой сервер или использовать одноразовые почтовики не составляет большого труда. Посему неизбежно возникнут проблемы с полностью валидными адресами такого формата.

```
"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"@[IPv6:2001:0db8:85a3:0000:0000:8a2e:0370:7334]
```

Но эта проблема выходит за рамки данной статьи, так что продолжим.

# Формат адреса

Отбросив домен, у нас осталась пользовательская часть, которую нужно проверить исходя из допустимого формата самих сервисов. Можно пойди двумя путями:

- Собрать максимально свободную регулярку и не мучаться.
- Или, чтоб не слать байты по заведомо не валидному адресу, проверить каждую запрещённую последовательность основываясь на таблице.

Как поступать решать вам, а таблица собственно приведена ниже.

- `min` минимальная длинна
- `max` максимальная длинна
- `chars` допустимые символы
- `first` допустимый первый символ
- `last` допустимый последний символ
- `!.` удалять ли точку при проверке
- `..` разрешена ли последовательность из двух точек
- `._` могут ли символы `.` и `_` находиться рядом
- `.-` ** могут ли символы . и - находиться рядом
- `.0` может ли точка соседствовать с цифрой
- `__` разрешена ли последовательность из двух `_`
- `_-` могут ли символы `_` и `-` находиться рядом
- `--` разрешена ли последовательность из двух `-`
- `.+` может ли в строке быть больше одной точки
- `+` игнорировать ли всё, что после знака `+`
- `l` количество не буквенных символов, после которого требуется минимум одна буква
- `t` тестировались ли данные руками

|  | min | max | chars | first | last | !. | .. | ._ | .- | .0 | __ | _- | -- | .+ | + | l | t |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| aol | 3 | 32 | a-z0-9._ | a-z | a-z0-9 | ❌ | ❌ | ❌ |  | ✅ | ❌ |  |  | ❌ | ❌ |  | ✅ |
| fastmail | 1 | 32 | a-z0-9_ | a-z | a-z0-9_ |  |  |  |  |  | ✅ |  |  |  | ✅ |  | ✅ |
| gmx | 3 | 40 | a-z0-9._- | a-z0-9 | a-z0-9 | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |  | ✅ |
| google | 6 | 30 | a-z0-9. | a-z0-9 | a-z0-9 | ✅ | ❌ |  |  | ✅ |  |  |  | ✅ | ✅ | 8 | ✅ |
| hey | 2 | 64 | a-z0-9. | a-z0-9 | a-z0-9 | ❌ | ❌ |  |  | ✅ |  |  |  | ✅ | ✅ |  | ✅ |
| hush | 1 | 45 | a-z0-9._- | a-z0-9 | a-z0-9 | ❌ | ❌ | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ | ✅ | ❌ |  | ❌ |
| icloud | 3 | 20 | a-z0-9._ | a-z | a-z0-9 | ❌ | ❌ | ✅ |  | ✅ | ❌ |  |  | ✅ | ✅ |  | ✅ |
| lycos | 1 | 32 | a-z0-9._- | a-z | a-z0-9 | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ | ✅ | ❌ | ✅ | ❌ |  | ❌ |
| mail_com | 3 | 40 | a-z0-9._- | a-z0-9 | a-z0-9 | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | 3 | ✅ |
| mail_ru | 5 | 31 | a-z0-9._- | a-z0-9 | a-z0-9 | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ✅ | ✅ |  | ✅ |
| mailfence | 4 | 40 | a-z0-9._- | a-z0-9_ | a-z0-9_- | ❌ | ❌ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |  | ✅ |
| meta_ua | 1 | 32 | a-z0-9._- | a-z | a-z0-9 | ❌ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |  | ✅ |
| microsoft | 1 | 64 | a-z0-9._- | a-z | a-z0-9_- | ❌ | ❌ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |  | ✅ |
| online_ua | 1 | 32 | a-z0-9.- | a-z0-9 | a-z0-9 | ❌ | ❌ |  | ❌ | ✅ |  |  | ❌ | ✅ | ❌ |  | ✅ |
| posteo | 3 | 60 | a-z0-9._- | a-z0-9 | a-z0-9_- | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ✅ | ✅ |  | ❌ |
| protonmail | 1 | 40 | a-z0-9._- | a-z0-9 | a-z0-9 | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ✅ | ✅ |  | ✅ |
| rambler | 3 | 32 | a-z0-9._- | a-z0-9 | a-z0-9 | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |  | ✅ |
| skiff | 1 | 30 | a-z0-9. | a-z0-9 | a-z0-9 | ✅ | ❌ |  |  | ✅ |  |  |  | ✅ | ✅ |  | ✅ |
| tutanota | 3 | 64 | a-z0-9._- | a-z0-9_ | a-z0-9_- | ❌ | ❌ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |  | ❌ |
| ukr_net | 1 | 32 | a-z0-9._- | a-z0-9_- | a-z0-9_- | ❌ | ❌ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |  | ✅ |
| vk | 5 | 31 | a-z0-9._- | a-z0-9 | a-z0-9 | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ | ✅ | ✅ |  | ✅ |
| yahoo | 4 | 32 | a-z0-9._ | a-z | a-z0-9 | ❌ | ❌ | ❌ |  | ✅ | ❌ |  |  | ✅ | ❌ |  | ✅ |
| yandex | 1 | 30 | a-z0-9.- | a-z | a-z0-9 | ❌ | ❌ |  | ❌ | ✅ |  |  | ❌ | ✅ | ✅ |  | ✅ |

# Домены

- `d` привязаны ли все домены к одному аккаунту

| | d | |
| --- | --- | --- |
| aol | ❌ | aol.com |
| fastmail | ❌ | fastmail.com |
|  | fastmail.cn |
|  | fastmail.co.uk |
|  | fastmail.com.au |
|  | fastmail.de |
|  | fastmail.es |
|  | fastmail.fm |
|  | fastmail.fr |
|  | fastmail.im |
|  | fastmail.in |
|  | fastmail.jp |
|  | fastmail.mx |
|  | fastmail.net |
|  | fastmail.nl |
|  | fastmail.org |
|  | fastmail.se |
|  | fastmail.to |
|  | fastmail.tw |
|  | fastmail.uk |
|  | fastmail.us |
|  | sent.com |
| gmx | ❌ | gmx.com |
|  | gmx.us |
| google | ✅ | gmail.com |
|  | googlemail.com |
| hey | ❌ | hey.com |
| hush | ❌ | hush.ai |
|  | hush.com |
|  | hushmail.com |
|  | hushmail.me |
|  | mac.hush.com |
| icloud | ❌ | icloud.com |
| lycos | ❌ | lycos.com |
| mail_com | ❌ | mail.com |
|  | email.com |
|  | usa.com |
|  | myself.com |
|  | consultant.com |
|  | post.com |
|  | europe.com |
|  | asia.com |
|  | iname.com |
|  | writeme.com |
|  | dr.com |
|  | engineer.com |
|  | cheerful.com |
|  | accountant.com |
|  | activist.com |
|  | adexec.com |
|  | allergist.com |
|  | alumni.com |
|  | alumnidirector.com |
|  | appraiser.net |
|  | archaeologist.com |
|  | arcticmail.com |
|  | artlover.com |
|  | asia.com |
|  | auctioneer.net |
|  | bartender.net |
|  | bikerider.com |
|  | birdlover.com |
|  | brew-meister.com |
|  | cash4u.com |
|  | chef.net |
|  | chemist.com |
|  | clubmember.org |
|  | collector.org |
|  | columnist.com |
|  | comic.com |
|  | computer4u.com |
|  | consultant.com |
|  | contractor.net |
|  | coolsite.net |
|  | counsellor.com |
|  | cyberservices.com |
|  | deliveryman.com |
|  | diplomats.com |
|  | disposable.com |
|  | dr.com |
|  | engineer.com |
|  | execs.com |
|  | fastservice.com |
|  | financier.com |
|  | fireman.net |
|  | gardener.com |
|  | geologist.com |
|  | graduate.org |
|  | graphic-designer.com |
|  | groupmail.com |
|  | hairdresser.net |
|  | homemail.com |
|  | hot-shot.com |
|  | instruction.com |
|  | instructor.net |
|  | insurer.com |
|  | job4u.com |
|  | journalist.com |
|  | legislator.com |
|  | lobbyist.com |
|  | minister.com |
|  | musician.org |
|  | myself.com |
|  | net-shopping.com |
|  | optician.com |
|  | orthodontist.net |
|  | pediatrician.com |
|  | photographer.net |
|  | physicist.net |
|  | planetmail.com |
|  | planetmail.net |
|  | politician.com |
|  | post.com |
|  | presidency.com |
|  | priest.com |
|  | programmer.net |
|  | publicist.com |
|  | qualityservice.com |
|  | radiologist.net |
|  | realtyagent.com |
|  | registerednurses.com |
|  | repairman.com |
|  | representative.com |
|  | rescueteam.com |
|  | salesperson.net |
|  | secretary.net |
|  | socialworker.net |
|  | sociologist.com |
|  | solution4u.com |
|  | songwriter.net |
|  | surgical.net |
|  | teachers.org |
|  | tech-center.com |
|  | techie.com |
|  | technologist.com |
|  | theplate.com |
|  | therapist.net |
|  | toothfairy.com |
|  | tvstar.com |
|  | umpire.com |
|  | webname.com |
|  | worker.com |
|  | workmail.com |
|  | writeme.com |
|  | 2trom.com |
|  | activist.com |
|  | aircraftmail.com |
|  | artlover.com |
|  | bikerider.com |
|  | birdlover.com |
|  | blader.com |
|  | boardermail.com |
|  | brew-master.com |
|  | brew-meister.com |
|  | bsdmail.com |
|  | catlover.com |
|  | chef.net |
|  | clubmember.org |
|  | collector.org |
|  | cutey.com |
|  | dbzmail.com |
|  | doglover.com |
|  | doramail.com |
|  | galaxyhit.com |
|  | gardener.com |
|  | greenmail.net |
|  | hackermail.com |
|  | hilarious.com |
|  | keromail.com |
|  | kittymail.com |
|  | linuxmail.org |
|  | lovecat.com |
|  | marchmail.com |
|  | musician.org |
|  | nonpartisan.com |
|  | petlover.com |
|  | photographer.net |
|  | snakebite.com |
|  | songwriter.net |
|  | techie.com |
|  | theplate.com |
|  | toke.com |
|  | uymail.com |
|  | computer4u.com |
|  | consultant.com |
|  | contractor.net |
|  | coolsite.net |
|  | cyberdude.com |
|  | cybergal.com |
|  | cyberservices.com |
|  | cyber-wizard.com |
|  | engineer.com |
|  | fastservice.com |
|  | graphic-designer.com |
|  | groupmail.com |
|  | homemail.com |
|  | hot-shot.com |
|  | housemail.com |
|  | humanoid.net |
|  | iname.com |
|  | inorbit.com |
|  | mail-me.com |
|  | myself.com |
|  | net-shopping.com |
|  | null.net |
|  | physicist.net |
|  | planetmail.com |
|  | planetmail.net |
|  | post.com |
|  | programmer.net |
|  | qualityservice.com |
|  | rocketship.com |
|  | solution4u.com |
|  | tech-center.com |
|  | techie.com |
|  | technologist.com |
|  | webname.com |
|  | workmail.com |
|  | writeme.com |
|  | acdcfan.com |
|  | artlover.com |
|  | chemist.com |
|  | diplomats.com |
|  | discofan.com |
|  | elvisfan.com |
|  | execs.com |
|  | hiphopfan.com |
|  | housemail.com |
|  | kissfans.com |
|  | madonnafan.com |
|  | metalfan.com |
|  | minister.com |
|  | musician.org |
|  | ninfan.com |
|  | ravemail.com |
|  | reborn.com |
|  | reggaefan.com |
|  | snakebite.com |
|  | songwriter.net |
|  | bellair.net |
|  | californiamail.com |
|  | dallasmail.com |
|  | nycmail.com |
|  | pacific-ocean.com |
|  | pacificwest.com |
|  | sanfranmail.com |
|  | usa.com |
|  | africamail.com |
|  | arcticmail.com |
|  | asia.com |
|  | asia-mail.com |
|  | australiamail.com |
|  | berlin.com |
|  | brazilmail.com |
|  | chinamail.com |
|  | dublin.com |
|  | dutchmail.com |
|  | englandmail.com |
|  | europe.com |
|  | europemail.com |
|  | germanymail.com |
|  | irelandmail.com |
|  | israelmail.com |
|  | italymail.com |
|  | koreamail.com |
|  | mexicomail.com |
|  | moscowmail.com |
|  | munich.com |
|  | polandmail.com |
|  | safrica.com |
|  | samerica.com |
|  | scotlandmail.com |
|  | spainmail.com |
|  | swedenmail.com |
|  | swissmail.com |
|  | torontomail.com |
|  | angelic.com |
|  | atheist.com |
|  | disciples.com |
|  | innocent.com |
|  | minister.com |
|  | muslim.com |
|  | priest.com |
|  | protestant.com |
|  | reborn.com |
|  | reincarnate.com |
|  | religious.com |
|  | saintly.com |
| mail_ru | ❌ | mail.ru |
|  | internet.ru |
|  | bk.ru |
|  | inbox.ru |
|  | list.ru |
| mailfence | ❌ | mailfence.com |
| meta_ua | ❌ | meta.ua |
| microsoft | ❌ | outlook.com |
|  | hotmail.com |
| online_ua | ❌ | online.ua |
| posteo | ❌ | posteo.at |
|  | posteo.be |
|  | posteo.ca |
|  | posteo.ch |
|  | posteo.cl |
|  | posteo.co |
|  | posteo.co.uk |
|  | posteo.com.br |
|  | posteo.cr |
|  | posteo.cz |
|  | posteo.de |
|  | posteo.dk |
|  | posteo.ee |
|  | posteo.es |
|  | posteo.eu |
|  | posteo.fi |
|  | posteo.gl |
|  | posteo.gr |
|  | posteo.hn |
|  | posteo.hr |
|  | posteo.hu |
|  | posteo.ie |
|  | posteo.in |
|  | posteo.is |
|  | posteo.it |
|  | posteo.jp |
|  | posteo.la |
|  | posteo.li |
|  | posteo.lt |
|  | posteo.lu |
|  | posteo.me |
|  | posteo.mx |
|  | posteo.my |
|  | posteo.net |
|  | posteo.nl |
|  | posteo.no |
|  | posteo.nz |
|  | posteo.org |
|  | posteo.pe |
|  | posteo.pl |
|  | posteo.pm |
|  | posteo.pt |
|  | posteo.ro |
|  | posteo.se |
|  | posteo.sg |
|  | posteo.si |
|  | posteo.tn |
|  | posteo.uk |
|  | posteo.us |
| protonmail | ❌ | proton.me |
|  | protonmail.com |
| rambler | ❌ | ro.ru |
|  | r0.ru |
|  | rambler.ru |
|  | rambler.ua |
|  | autorambler.ru |
|  | myrambler.ru |
| skiff | ❌ | skiff.com |
| tutanota | ❌ | tutanota.com |
|  | tutanota.de |
|  | tutamail.com |
|  | tuta.io |
|  | keemail.me |
| ukr_net | ❌ | ukr.net |
| vk | ❌ | vk.com |
| yahoo | ❌ | yahoo.com |
|  | ymail.com |
|  | rocketmail.com |
| yandex | ✅ | ya.ru |
|  | yandex.ru |
|  | yandex.by |
|  | yandex.com |
|  | yandex.kz |
|  | narod.ru |
