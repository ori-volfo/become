var stats = (function(){
    "use strict";

    let DBapiPath;
    let dataArr;

    const init = (params)=>{
        DBapiPath = params.projectPath+'db.api.php';

        $.get(DBapiPath + '?query=get_users_stats',(data)=>{
            dataArr = JSON.parse(data);

            dataArr = Utils.addAgeField(dataArr);

            initCitiesChart();
            initCountriesChart();
            initAgesChart();
        });
    };

    const initCitiesChart = ()=>{
        let citiesArr = [];
        let citiesCount = {};
        dataArr.map(user=>citiesArr.push(user.city_name));
        citiesArr.forEach(el => citiesCount[el] = 1  + (citiesCount[el] || 0));

        populatePieChart('#citiesChart', citiesCount);
    };

    const initCountriesChart = ()=>{
        let countriesArr = [];
        let countriesCount = {};

        dataArr.map(user=>countriesArr.push(user.country_name));
        countriesArr.forEach(el => countriesCount[el] = 1  + (countriesCount[el] || 0));

        populatePieChart('#countriesChart', countriesCount);
    };

    const initAgesChart = ()=>{
        let agesArr = [];
        dataArr.map(user=>agesArr.push(user.age));

        populateBarChart('#agesChart',agesArr.sort());
    };

    /**
     * This function sets the data for the target pie chart
     * @param canvasElem - target canvas DOM element
     * @param dataObj - dataset of labels and values { label1: number1, label2: number2, ...}
     */
    const populatePieChart = (canvasElem, dataObj)=>{
        let ctx = $(canvasElem)[0].getContext('2d');
        let myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                datasets: [{
                    data: Object.values(dataObj), // number of graph items
                    backgroundColor: Object.keys(dataObj).map(item=>intToRGB(hashCode(item)*5)) //creates RGB color for each label. multiplied by 5 to diversify the colors.
                }],
                labels: Object.keys(dataObj), // graph labels
            },
        });
    };

    const populateBarChart = (canvasElem, dataArr)=>{
        let ctx = $(canvasElem)[0].getContext('2d');
        let myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                datasets: [{
                    label: '',
                    data: dataArr,
                    backgroundColor: dataArr.map(item=>intToRGB(item*1234567)) //creates RGB color for each bar. multiplied by 1234567 to diversify the colors.
                }],
                labels: dataArr, // graph labels
            },
            options: {
                legend: {
                    display: false,
                },
                maintainAspectRatio: false
            }
        });
    };

    /**
     * This function gets a string as an input and hashes it to a number as an output
     * @param str
     * @returns {number}
     */
    const hashCode = (str)=> {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        return hash;
    };
    /**
     * This function gets an integer and returns an RGB string
     * @param number
     * @returns {string}
     */
    const intToRGB = (number)=>{
        let c = (number & 0x00FFFFFF)
            .toString(16)
            .toUpperCase();
        return '#'+("00000".substring(0, 6 - c.length) + c);
    };

    return{
        init
    }
})();