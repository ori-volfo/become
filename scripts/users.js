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
            usersArr = addAgeField(usersArr);

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

    /**
     * Gets a users array and adds a calculated age field
     * @param users
     * @returns {Array}
     */
    const addAgeField = (users)=>{
        return users.map(user=> {
            user.age = calcAge(user.birth_date);
            return user;
        });
    };
    /**
     * Gets a string birth date in the format yyyy-mm-dd and returns current age
     * @param birthDate
     * @returns {number}
     */
    const calcAge = (birthDate) => {
            const date_array = birthDate.split('-');
            const years_elapsed = (new Date() - new Date(date_array[0],date_array[1],date_array[2]))/(1000*60*60*24*365);
            return Math.floor(years_elapsed);
    };

    return{
        init
    }
})();