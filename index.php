<?php

########################################
### Copyright © 2024 Maxim Rysevets. ###
########################################

namespace microservices;

require_once('init.php');

?>

<style>
    body {
        margin: 0;
        padding: 30px;
        font-family: Arial;
        font-size: 1rem;
    }
</style>

<?php

###############
### balance ###
###############

$all_uid = User::get_all_uid();

if (is_array($all_uid) && count($all_uid) === 0) print 'No users!';
if (is_array($all_uid) && count($all_uid)  >  0) { ?>
    <h2>Balance of users</h2>
    <ul>
        <? foreach ($all_uid as $c_row) { ?>
                <li>
                    <a href="/get_balance.php?uid=<?      print $c_row['uid'] ?>"
                       target="balance_<?                 print $c_row['uid'] ?>">
                       Show balance of user with UID = <? print $c_row['uid'] ?>
                </a>
            </li>
        <? } ?>
    </ul>
<?php }

###################
### short links ###
###################

?>

<h2>JSON statuses</h2>

<ul>
    <li> <a href="/get_errors.php?type=not_200"        target="errors">not 200</a>        </li>
    <li> <a href="/get_errors.php?type=empty_json"     target="errors">empty json</a>     </li>
    <li> <a href="/get_errors.php?type=invalid_json"   target="errors">invalid json</a>   </li>
    <li> <a href="/get_errors.php?type=status_ok"      target="errors">status ok</a>      </li>
    <li> <a href="/get_errors.php?type=status_warning" target="errors">status warning</a> </li>
    <li> <a href="/get_errors.php?type=status_error"   target="errors">status error</a>   </li>
    <li> <a href="/get_errors.php?type=status_unknown" target="errors">status unknown</a> </li>
</ul>



<h2>Short Links</h2>

<style>
    #report {
        display: block;
        margin: 10px 0;
        padding: 20px;
        border: 3px solid red;
    }
    [data-type='forms-grid'] {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    [data-type='forms-grid'] form {
        flex: 200px;
        margin: 0;
        padding: 20px;
        border: 2px solid black;
        background: #eee;
    }
    [data-type='forms-grid'] form label {
        display: block;
    }
    [data-type='forms-grid'] form input,
    [data-type='forms-grid'] form select {
        appearance: none;
        box-sizing: border-box;
        width: 100%;
        margin: 10px 0;
        padding: 5px;
        border: 2px solid black;
        border-radius: 0;
        font-size: 1rem;
    }
    [data-type='forms-grid'] button {
        margin: 10px 0;
        padding: 10px 20px;
        cursor: pointer;
        border: 0;
        border-radius: 20px;
        color: white;
        font-size: .9rem;
        background: black;
    }
</style>

<x-messages id="report">
</x-messages>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let mount = document.getElementById('report');
        testApi('http://unknown_domain',                                  {}, 'post', mount);
        testApi('http://microservice.loc/get_errors.php?type=not_200',        {}, 'post', mount);
        testApi('http://microservice.loc/get_errors.php?type=empty_json',     {}, 'post', mount);
        testApi('http://microservice.loc/get_errors.php?type=invalid_json',   {}, 'post', mount);
        testApi('http://microservice.loc/get_errors.php?type=status_ok',      {}, 'post', mount);
        testApi('http://microservice.loc/get_errors.php?type=status_warning', {}, 'post', mount);
        testApi('http://microservice.loc/get_errors.php?type=status_error',   {}, 'post', mount);
        testApi('http://microservice.loc/get_errors.php?type=status_unknown', {}, 'post', mount);
    });

    function onSendLink() {
        event.preventDefault();
        let mount = document.getElementById('report');
        let service = 'http://microservice.loc/get_short_link.php';
        let method = event.target.method;
        let data = {};
        Array.prototype.forEach.call(event.target.elements, element => {
            if (element instanceof HTMLInputElement ||
                element instanceof HTMLSelectElement)
                data[element.name] = element.value;
        });
        testApi(service, data, method, mount);
    }

    function testApi(service, data, method, mount) {
        console.log(JSON.stringify(data));
        fetch(service, {
            method: method,
            headers: {'Content-Type': 'application/json'},
            body: method === 'post' ? JSON.stringify(data) : null
        }).then((response) => {
            response.json().then((data) => {
                let result = JSON.stringify(data);
                if      (data.status === 'ok'     ) mount.innerHTML += `${service} | Service Ok: ${result}<br>`;
                else if (data.status === 'warning') mount.innerHTML += `${service} | Service Warning: ${result}<br>`;
                else if (data.status === 'error'  ) mount.innerHTML += `${service} | Service Error: ${result}<br>`;
                else                                mount.innerHTML += `${service} | Service Error: Unknown status<br>`;
            }).catch(error => { mount.innerHTML += `${service} | JSON Parse Error: ${error.message}<br>`; });
        })    .catch(error => { mount.innerHTML += `${service} | Network Error: ${error.message}<br>`; });
    }
</script>

<div data-type="forms-grid">
    <form method="POST" onsubmit="onSendLink()">
        <label>No link</label>
        <button type="submit">Test</button>
    </form>

    <form method="GET" onsubmit="onSendLink()">
        <label>Via GET</label>
        <input type="text" name="link" value="http://example.com">
        <button type="submit">Test</button>
    </form>

    <form method="POST" onsubmit="onSendLink()">
        <label>Variants</label>
        <select name="link">
            <option value="">incorrect link: empty value</option>
            <option value="http://">incorrect link: http://</option>
            <option value="http://domain/path?query#anchor<?php print str_repeat('abc', 2047); ?>">incorrect link: to long value...</option>
            <option value="http://domain">correct link: http://domain</option>
            <option value="http://domain/path">correct link: http://domain/path</option>
            <option value="http://domain/path?query">correct link: http://domain/path?query</option>
            <option value="http://domain/path?query#anchor">correct link: http://domain/path?query#anchor</option>
            <option value="<?php print base64_decode('aHR0cHM6Ly90ZXN0c2FmZWJyb3dzaW5nLmFwcHNwb3QuY29tL3MvbWFsd2FyZS5odG1s'); ?>">correct link: !!! malware !!!</option>
        </select>
        <button type="submit">Test</button>
    </form>

    <form method="POST" onsubmit="onSendLink()">
        <label>Custom value</label>
        <input type="text" name="link" value="<?php print base64_decode('aHR0cHM6Ly90ZXN0c2FmZWJyb3dzaW5nLmFwcHNwb3QuY29tL3MvbWFsd2FyZS5odG1s'); ?>">
        <button type="submit">Test</button>
    </form>
</div>