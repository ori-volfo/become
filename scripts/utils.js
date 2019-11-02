var Utils = (function(){
    "use strict";

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
        calcAge
    }
})();