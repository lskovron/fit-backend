jQuery(document).ready(function(){

    //constants
    const COLORS = {
        cognitive: [ //blues
            "#23239a",
            "#3838a4", 
            "#4e4eae",
            "#6565b8", 
            "#6565b8", 
            "#6565b8", 
            "#6565b8", 
            "#9191cc", 
            "#bdbde0"
        ],
        emotional: [ //reds
            "#ee2020",
            "#ef3636",
            "#f14c4c",
            "#f36262",
            "#f36262",
            "#f36262",
            "#f36262",
            "#f47979",
            "#f68f8f",
        ],
        physical: [ //oranges
            "#f98525",
            "#f9913a",
            "#fa9d50",
            "#faa966",
            "#fbb57c",
            "#fcc292",
            "#fcc292",
            "#fcc292",
            "#fccea7",
        ],
        financial: [ //greens
            "#3d8b31",
            "#509645",
            "#63a25a",
            "#63a25a",
            "#63a25a",
            "#63a25a",
            "#77ad6e"
        ],
        spiritual: [ //turquoise
            "#40e0d0",
            "#66e6d9",
            "#8cece2",
            "#9fefe7",
            "#c5f5f0",
            "#c5f5f0",
            "#c5f5f0",
            "#c5f5f0",
            "#d8f8f5"
        ]
    }

    let initialOptions = {
        chart: {
          type: "variablepie",
          margin: [0, 200, 0, 0],
        },
      
        title: {
          text: null
        },
      
        legend: {
          align: "right",
          verticalAlign: "middle",
          layout: "vertical",
        },
        plotOptions: {
          series: {
            stacking: "normal",
            dataLabels: {
              enabled: false,
            },
            showInLegend: true,
            size: "100%",
            point: {
              events: {
                legendItemClick: function() {
                  return false;
                }
              }
            }
          }
        },
        
        series: [
          {
            minPointSize: 10,
            innerSize: "3%",
            zMin: 0,
            name: "Subdomain score",
            tooltip: {
              headerFormat: '<span style="color:{point.color}">●</span><span style="font-size: 12px;font-weight:bold;"> {point.key}</span><br/>',
              pointFormat: '<br/>Score: {point.z}<br/>',
              valueDecimals: 2
            }
          }
        ]
    };

    //define functions
    const reformatUrlData = urlObj => {
        let wpPostFormat = {}
        let results = {
            cognitive: {},
            emotional:{},
            physical:{},
            financial:{},
            spiritual:{}
        }

        for (var property in urlObj) {
            if (urlObj.hasOwnProperty(property)) {
            switch(property.substring(0,2)){
                case 'c-':
                    results["cognitive"][property.substring(2)] = parseFloat(urlObj[property]);
                    break;
                case 'e-':
                    results["emotional"][property.substring(2)] = parseFloat(urlObj[property]);
                    break;
                case 'p-':
                    results["physical"][property.substring(2)] = parseFloat(urlObj[property]);
                    break;
                case 'f-':
                    results["financial"][property.substring(2)] = parseFloat(urlObj[property]);
                    break;
                case 's-':
                    results["spiritual"][property.substring(2)] = parseFloat(urlObj[property]);
                    break;
                }
            }
            if(property.charAt(1) === '-'){
                //scores
                wpPostFormat[property.substring(2)] = parseFloat(urlObj[property]);
            } else {
                //demo
                wpPostFormat[property] = urlObj[property];
            }
        }

        for( var key in results ){
            if ( Object.entries(results[key]).length === 0 ){
                delete results[key];
            }
        }

        let overallScores = {};
        Object.keys(wpPostFormat).map((key)=>{
            if(key.includes('score') && !key.includes('overall') && !key.includes('balance')){
                overallScores[key] = wpPostFormat[key]
            }
        })

        wpPostFormat['balance-score'] = getBalanceScore(overallScores);

        return {
            highchartsRaw: results,
            wpRaw: wpPostFormat
        }
    }
    
    const capitalizeWords = str => {
    return str.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
    } 

    const generateChartData = results => {
        let data = [];
        let colors = [];
    
        const getColorArray = dimension => {
            if(results[dimension]){
                const keys = Object.keys(results[dimension]);
                const subCount = keys.length;
                return COLORS[dimension].slice(-subCount);
            } else {
                return [];
            }
        }
    
        const formatData = dimensionResults => {
            if(dimensionResults){
                const keys = Object.keys(dimensionResults);
                const subCount = keys.length;
          
                const dataArray = keys.map((sub)=>{
                  let score = dimensionResults[sub];
                  let label = capitalizeWords(sub.replace(/\-/g,' '));
                  if(label === 'Bmi') label = 'BMI'
                  return {
                    name: label,
                    y: 72/subCount,
                    z: score
                  }
                })
                
                return dataArray;
            } else {
                return [];
            }
        }
        
        data = [
          ...formatData(results.cognitive),
          ...formatData(results.emotional),
          ...formatData(results.physical),
          ...formatData(results.financial),
          ...formatData(results.spiritual)
        ]
        colors = [
          ...getColorArray('cognitive'),
          ...getColorArray('emotional'),
          ...getColorArray('physical'),
          ...getColorArray('financial'),
          ...getColorArray('spiritual'),
        ]

        return {
            data: data,
            colors: colors
        }
    }
    
    const createSubmission = urlObj => {

        var data = {
            action: 'submit_results',
            args: urlObj,
            submit_results_nonce: SubmitResultsAjax.submit_results_nonce
        };
        
        // $loader.show();
        var jqxhr = jQuery.post(SubmitResultsAjax.ajaxurl, data);
        jqxhr.done(function(response) {
            if( response ) {
                console.log(response);
            }
            else {
                // showErrorBlock();
            }
            }).fail(function() {
                // showErrorBlock();
            }).always(function() {
                // $loader.hide();
            });
    }

    const updateQueryStringParam = (key, value) => {

        var baseUrl = [location.protocol, '//', location.host, location.pathname].join(''),
            urlQueryString = document.location.search,
            newParam = key + '=' + value,
            params = '?' + newParam;
    
        // If the "search" string exists, then build params from it
        if (urlQueryString) {
    
            updateRegex = new RegExp('([\?&])' + key + '[^&]*');
            removeRegex = new RegExp('([\?&])' + key + '=[^&;]+[&;]?');
    
            if( typeof value == 'undefined' || value == null || value == '' ) { // Remove param if value is empty
    
                params = urlQueryString.replace(removeRegex, "$1");
                params = params.replace( /[&;]$/, "" );
    
            } else if (urlQueryString.match(updateRegex) !== null) { // If param exists already, update it
    
                params = urlQueryString.replace(updateRegex, "$1" + newParam);
    
            } else { // Otherwise, add it to end of query string
    
                params = urlQueryString + '&' + newParam;
    
            }
    
        }
        window.history.replaceState({}, "", baseUrl + params);
    };

    const setupUi = rootObj => {

        let overallScores = {};
        Object.keys(rootObj).map((key)=>{
            if(key.includes('score') && !key.includes('overall') && !key.includes('balance')){
                overallScores[key] = rootObj[key]
            }
        })

        if(rootObj['overall-score']){
            jQuery('#overall .total').text(rootObj['overall-score'])
        }

        const highest = Object.keys(overallScores).reduce((a, b) => {return overallScores[a] > overallScores[b] ? a : b });
        const lowest = Object.keys(overallScores).reduce((a, b) => {return overallScores[a] < overallScores[b] ? a : b });

        const highestSelector = `#${highest.substr(0,1)}-high`
        const lowestSelector = `#${lowest.substr(0,1)}-low`

        jQuery(highestSelector).removeClass('result-analysis').show();
        jQuery(lowestSelector).removeClass('result-analysis').show();
        jQuery('.result-analysis').remove();

        jQuery('#high-title span').addClass(highest);
        jQuery('#low-title span').addClass(lowest);
        jQuery('#high-title span').text(`${capitalizeWords( highest.replace('-score','') ) } (${overallScores[highest]})`)
        jQuery('#low-title span').text(`${capitalizeWords( lowest.replace('-score','') ) } (${overallScores[lowest]})`)

        jQuery('#balance span').text(`${getBalanceScore(overallScores)}%`);
        jQuery('#email-address').text(rootObj['email']);

    }

    const getBalanceScore = overallScores => {
        let obj = Object.assign({},overallScores);
        delete obj['overall-score'];
        let arr = Object.keys(obj).map((key)=>obj[key])
        return (100-getStandardDeviation(arr)).toFixed(2);
    }

    const getStandardDeviation  = array => {
        const n = array.length
        const mean = array.reduce((a, b) => a + b) / n
        return Math.sqrt(array.map(x => Math.pow(x - mean, 2)).reduce((a, b) => a + b) / n) * (100/mean)
    }

    const getEmailData = rootObj => {
        let overallScores = {};
        let emailData = {};

        Object.keys(rootObj).map((key)=>{
            if(key.includes('score') && !key.includes('overall') && !key.includes('balance')){
                overallScores[key] = rootObj[key]
            }
        })

        const highest = Object.keys(overallScores).reduce((a, b) => {return overallScores[a] > overallScores[b] ? a : b });
        const lowest = Object.keys(overallScores).reduce((a, b) => {return overallScores[a] < overallScores[b] ? a : b });
        const balance = getBalanceScore(overallScores);

        emailData = {
            overall: rootObj['overall-score'],
            balance: balance,
            email: rootObj['email'],
            participant: rootObj['participant'],
            'highest-score': rootObj[highest],
            'highest-dim': highest.replace('-score',''),
            'lowest-score': rootObj[lowest],
            'lowest-dim': lowest.replace('-score',''),
        } 

        return emailData;
        
    }

    //execute functions
    const assessmentData = Object.fromEntries(new URLSearchParams(location.search));
    const formatData = reformatUrlData(assessmentData);
    const chartRaw = formatData.highchartsRaw;
    const wpPostData = formatData.wpRaw;
    const chartFormatted = generateChartData(chartRaw);
    const emailData = getEmailData(wpPostData);

    if( assessmentData.hasOwnProperty('t-overall-score') ) {
        //prepare data
        
        const newDataSet = Object.assign(initialOptions,{});
        newDataSet.series[0].data = chartFormatted.data;
        newDataSet.colors = chartFormatted.colors;

        //UI setup
        setupUi(wpPostData);
        Highcharts.chart('highcharts-container', newDataSet);

        //create data entry
        if(assessmentData.hasOwnProperty('nodupe')){
            createSubmission(wpPostData);
            updateQueryStringParam( 'nodupe', null );
        }
    }

    //interactions
    jQuery('#send-email').click(function(e){
        e.preventDefault();

        var data = {
            action: 'send_email',
            args: emailData,
            send_email_nonce: SendEmailAjax.send_email_nonce
        };
        
        // $loader.show();
        var jqxhr = jQuery.post(SendEmailAjax.ajaxurl, data);
        jqxhr.done(function(response) {
            if( response ) {
                console.log(response);
            }
            else {
                // showErrorBlock();
            }
            }).fail(function() {
                // showErrorBlock();
            }).always(function() {
                // $loader.hide();
            });
    })

})

