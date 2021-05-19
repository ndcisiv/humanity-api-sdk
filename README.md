# Humanity v2 API SDK for PHP
An SDK for the Humanity v2 API

## Usage

```
$humanity = new \Ndcisiv\HumanityAPI2(
    [
        'client_id'     =>  'yourclientidgoeshere',
        'client_secret' =>  'yourclientsecretgoeshere',
        'grant_type'    =>  'password',
        'username'      =>  'user@domain.com',
        'password'      =>  'supersecretpassword']
);

$me = $humanity->getMe();

echo $me->data->name;
```
