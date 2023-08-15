# Анатомия email

Почтовые адреса это очень древнее изобретение, которое застало ещё живых динозавров. Поэтому имеет множество интересных
стандартов, которые нет смысла читать.
В дикой природе, мы чаще всего встречаемся с почтой у популярного провайдера ~~корпорации добра~~. Для удобства,
обозначим части почтового адреса таким спосомбом:

`user+detail@domain`

Часть после `@` нам неинтересна, посему разберём остальное.

## user

Чаще всего это транслитерация имени пользователя. Но есть один интересный ньюанс, это точка.
В корпорации добра, он не является значащим символом и посему `user@gmail.com` и `u.s.e.r@gmail.com` расцениваются как
один аккаунт.
Благодаря чему, в сервисах с криворукими разработчиками вы можете нарегестрировать сколь угодно аккаунтов на один email
адрес.

## detail

Деды говорят, что помнят времена, когда плюс являлся обычным символом в почтовом адресе. Но сейчас ушлые капиталисты
присвоили его для своих грязных делишек.
Всё что находится между знаками `+` и `@` игнорируется при определении акканута, и обрабатывается провайдером отдельно.
Обычно письмо ложат в одноимённую папку. Но это не точно.
Возвращаясь у криворуким разработчикам, плюс так же позволяет регистрировать бесконечное количество аккаунтов, но
зачастую эти упыри просто блокируют этот символ.

# Формат электронных адресов

- **.** - является ли точка значащим символом: ✅ да, ⛔ нет.
- **+** - поддержка **detail** :  ✅ да, ⛔ нет.
- **r** - разрешено ли два спецсимвола подряд :  ✅ да, ⛔ нет.
- **min** - минимальная длина.
- **max** - максимальная длина.
- **all** - разрешённые символы.
- **first** - разрешённый первый символ.
- **last** - разрешённый первый символ.

|         | .  | + | r | min | max | all       | first  | last   |
|---------|----|---|---|-----|-----|-----------|--------|--------|
| Google  | ⛔️ | ✅ | ⛔ | 6   | 30  | a-z0-9    | a-z0-9 | a-z0-9 |
| Yandex  | ✅  | ✅ | ⛔ | 1   | 30  | a-z0-9._- | a-z    | a-z0-9 |
| Mail.ru | ✅  | ✅ | ⛔ | 5   | 31  | a-z0-9._- | a-z0-9 | a-z0-9 |
| Vk      | ✅  | ✅ | ⛔ | 5   | 32  | a-z0-9._- | a-z0-9 | a-z0-9 |
| Rambler | ✅  | ⛔ | ⛔ | 3   | 32  | a-z0-9._- | a-z0-9 | a-z0-9 |

- *Всё выше перечисленное относится только к **user** части*.
- *Все email принято приводить к нижнему регистру, так что изначально предполагаем, что это было заранее сделано.*
- *По допустимым символам vk не удалось найти исчерпывающей ниформации, но так как он интегрирован с `mail.ru`, то будем полагать, что его правилам он и подчиняется.*

# Синонимы доменов

Все уже давно наслышаны, о [ya.ru](ya.ru), который очень удобно пинговать из-за его длины. Но более интересный факт в
том, что письмо отправленное на `user@ya.ru` и `user@yandex.ru` попадёт на один аккаунт.
Ниже представлен спиок подобных синонимов.

|        |                |
|--------|----------------|
| Google | gmail.com      |
|        | googlemail.com |
| Yandex | ya.ru          |
|        | yandex.ru      |
|        | yandex.by      |
|        | yandex.com     |
|        | yandex.kz      |
|        | narod.ru       |
