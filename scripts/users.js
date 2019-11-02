var users = (function(){
    "use strict";

    let DBapiPath;
    let usersArr;
    let $usersTable;

    const init = (params)=>{
        $usersTable = $('#users');
        DBapiPath = params.projectPath+'db.api.php';

        $.get(DBapiPath + '?query=get_users_data',(data)=>{
            usersArr = JSON.parse(data);
            usersArr = Utils.addAgeField(usersArr);

            populateUsersTable(usersArr);
            $usersTable.DataTable();
        });
    };

    const populateUsersTable = (usersArr) => {
        let html = '';
        usersArr.forEach(user=>{
            html +=
                `<tr>
                    <td>${user.first_name}</td>
                    <td>${user.last_name}</td>
                    <td>${user.email}</td>
                    <td>${user.phone}</td>
                    <td>${user.age}</td>
                    <td>${user.city_name}</td>
                    <td>${user.country_name}</td>
                </tr>`;

        });
        $usersTable.find('.tbody').append(html);
    };

    return{
        init
    }
})();