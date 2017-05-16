<h2>
    Steps for import data
</h2>
<ul>
    <li>
        Choose json file
    </li>
    <li>
        Press "Submit" button
    </li>
    <li>
        If any errors are present please fix it and start from the beginning
    </li>
    <li>
        Press "Start" button for start import
    </li>
    <li>
        You will see entire progress. When tje process will be finished please check the results on Business Manage menu
    </li>
    <li>
        You will be able to delete business on Business Manage page
    </li>
</ul>
<h2>
    Recommendations
</h2>
<h3>
    JSON
</h3>
<ul>
    <li>
        user
    </li>
    <li>
        email - email which user specify in "Sign up" form
    </li>
    <li>
        business
        <ul>
            <li>is_home - set this field to 1 only if the business is home or field should be absent</li>
            <li>ind_id - Industry ID. look at the Industry Dictionary (bellow)</li>
            <li>suite - is not required field. if there are no suite then this field should be absent</li>
            <li>phone - keep this format - (000) 000 - 0000</li>
            <li>contact_email - business email</li>
            <li>yelp_url - if business was not registered in yelp then "yelp_url", "vanity_name", "vanity_changed"
                fields should be absent
            </li>
        </ul>
    </li>
    <li>
        operation - all days are required ("day": 0 is monday, active: 1 - closed, 0 - opened)
    </li>
    <li>
        profile_photo - file_name - unique file name in photos folder
    </li>
    <li>
        photos - file_name - unique file name in photos folder
    </li>
    <li>
        menu
        <ul>
            <li>description, disclaimer - can be absent</li>
            <li>category</li>
            <ul>
                <li>description, disclaimer - can be absent</li>
            </ul>
            <li>service</li>
            <ul>
                <li>title, price - required fields</li>
            </ul>
        </ul>
    </li>
</ul>
<p>
    Newline delimiter for multi-line fields (description, disclaimer) is "\n".
</p>

<p>
    if you want to upload more than 1 business you should separate it. for example<br/>
    [<br/>
    {<br/>
    "user": {<br/>
    "email": "business@gmail.com",<br/>
    "password": "1234567890"<br/>
    },<br/>
    "business": {<br/>
    ...<br/>
    }<br/>
    ...<br/>
    },<br/>
    {<br/>
    "user": {<br/>
    "email": "another_business@gmail.com",<br/>
    "password": "1234567890"<br/>
    },<br/>
    "business": {<br/>
    ...
    }<br/>
    ...<br/>
    }<br/>
    ]<br/>
</p>

<h3>Industry Dictionary</h3>
<ul>
    <li>15 Day Spa</li>
    <li>16 Massage</li>
    <li>17 Hair Salon</li>
    <li>18 Nail Salon</li>
    <li>19 Skin Care</li>
    <li>20 Hair Removal</li>
    <li>21 Other</li>
    <li>22 Barber</li>
</ul>
