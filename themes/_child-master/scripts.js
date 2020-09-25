jQuery(document).ready(function(){

    //constants
    const COLORS = {
        cognitive: [ //oranges
            "#ff3a00",
            "#ff4a00",
            "#ff5a00",
            "#ff6a00",
            "#ff7e00",
            "#ff9400",
            "#ffb100",
            "#ffd200"
        ],
        emotional: [ //blues
            "#00296f",
            "#00396f",
            "#004980",
            "#005f92",
            "#007aa9",
            "#009cc1",
            "#08c6de"
        ],
        physical: [ //reds
            "#a11500",
            "#b51800",
            "#c01a00",
            "#cb1c00",
            "#d72100",
            "#e43304",
            "#f26b3e"
        ],
        financial: [ //greens
            "#004c00",
            "#005d00",
            "#006e00",
            "#008200",
            "#009906",
            "#1cb423",
            "#68d566"
        ],
        spiritual: [ //blue
            "#01188d",
            "#02188d",
            "#071d9c",
            "#1224ac",
            "#2733be",
            "#4d52d1",
            "#8e8ee7"
        ]
    }

    let initialOptions = {
        chart: {
          type: "variablepie",
          margin: [0, 200, 20, 0]
        },
      
        title: {
          text: null
        },
      
        legend: {
          align: "right",
          verticalAlign: "middle",
          layout: "vertical",
          rtl: true,
          labelFormat: '{name} <span style="opacity: 0.4;">({z})</span>',
          itemStyle: {
              fontSize:"11px"
          }
        },
        plotOptions: {
          series: {
            innerSize: 0,
            stacking: "normal",
            showInLegend: true,
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
            minPointSize: 40,
            innerSize: 0,
            sizeBy: "radius",
            name: "Subdomain score",
            dataLabels: {
              enabled: false,
            },
            tooltip: {
              headerFormat: '<span style="color:{point.color}">●</span><span style="font-size: 12px;font-weight:bold;"> {point.key}</span><br/>',
              pointFormat: '<br/>Score: {point.z}<br/>',
              valueDecimals: 2
            },
          },
          {
            showInLegend: false,
            type: "variablepie",
            size: "100%",
            innerSize: "100%",
            minPointSize: 0,
            borderSize: 1,
            borderColor: '#000',
            dataLabels: {
                enabled: true,
                format: "100%",
                enabled: true,
                connectorPadding: 0,
                connectorWidth: 0,
                distance: -3
            },
            enableMouseTracking: false,
            data: [{
                y: 100,
                z: 0,
                },
            ],
            colors: ['rgba(0,0,0,0)']
          },
          {
            showInLegend: false,
            type: "variablepie",
            size: "100%",
            innerSize: "75%",
            minPointSize: 0,
            dataLabels: {
                enabled: true,
                format: '75%',
                connectorPadding: 0,
                connectorWidth: 0,
                distance: -3
            },
            enableMouseTracking: false,
            data: [{
                y: 100,
                z: 75,
                },
            ],
            borderSize: 1,
            borderColor: '#000',
            colors: ['rgba(0,0,0,0)'],
          },
          {
            showInLegend: false,
            type: "variablepie",
            size: "100%",
            innerSize: "50%",
            minPointSize: 0,
            dataLabels: {
                enabled: true,
                format: "50%",
                enabled: true,
                connectorPadding: 0,
                connectorWidth: 0,
                distance: -3
            },
            enableMouseTracking: false,
            data: [{
                y: 100,
                z: 50,
                },
            ],
            borderSize: 1,
            borderColor: '#000',
            colors: ['rgba(0,0,0,0)'],
          },
          {
            showInLegend: false,
            type: "variablepie",
            size: "100%",
            innerSize: "25%",
            minPointSize: 0,
            dataLabels: {
                enabled: true,
                format: "25%",
                enabled: true,
                connectorPadding: 0,
                connectorWidth: 0,
                distance: -3
            },
            enableMouseTracking: false,
            data: [{
                y: 100,
                z: 25,
                },
            ],
            borderSize: 1,
            borderColor: '#000',
            colors: ['rgba(0,0,0,0)'],
          },
        ],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    chart: {
                        margin: [0, 0, 300, 0],
                        height: 600
                    },
                    legend: {
                        align: 'center',
                        verticalAlign: 'bottom',
                        layout: 'horizontal'
                    }
                }
            }]
        }
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

    const updateQueryStringParam = (key, value, saveAs = false) => {

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
        if( saveAs ) return params;
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
            jQuery('#overall .total').text(rootObj['overall-score']);
            jQuery('#result-section .total').text(rootObj['overall-score']);
        }

        const highest = Object.keys(overallScores).reduce((a, b) => {return overallScores[a] > overallScores[b] ? a : b });
        const highestSelector = `.archetype-container img.${highest.replace('-score','')}`;

        jQuery(highestSelector).show();
        jQuery('#result-section .balance').text(`${getBalanceScore(overallScores)}%`);
        jQuery('#email-address').text(rootObj['email']);

        Object.keys(overallScores).forEach(key=>{
            let selector = `.dim-score #${key}`;
            jQuery(selector).text(overallScores[key]);
        })


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
            'highestDim': highest.replace('-score',''),
            'lowestDim': lowest.replace('-score',''),
            cognitive: overallScores['cognitive-score'],
            emotional: overallScores['emotional-score'],
            physical: overallScores['physical-score'],
            financial: overallScores['financial-score'],
            spiritual: overallScores['spiritual-score'],
            'activityLevel': rootObj['activity-level'],
            'aerobicActivity': rootObj['aerobic-activity'],
            'attention': rootObj['attention'],
            'autonomy': rootObj['autonomy'],
            'compassionEmpathy': rootObj['compassion-empathy'],
            'connection': rootObj['connection'],
            'currentEmotionalHealth': rootObj['current-emotional-health'],
            'effortControl': rootObj['effort-control'],
            'emotionalScore': rootObj['emotional-score'],
            'financialScore': rootObj['financial-score'],
            'forgiveness': rootObj['forgiveness'],
            'gratitudePositivity': rootObj['gratitude-positivity'],
            'increaseHappiness': rootObj['increase-happiness'],
            'intellectualEngagement': rootObj['intellectual-engagement'],
            'learningStrategies': rootObj['learning-strategies'],
            'mindset': rootObj['mindset'],
            'nonPecuniary': rootObj['non-pecuniary'],
            'nutrition': rootObj['nutrition'],
            'nutritionKnowledge': rootObj['nutrition-knowledge'],
            'presence': rootObj['presence'],
            'purpose': rootObj['purpose'],
            'reduceSadness': rootObj['reduce-sadness'],
            'selfCompassion': rootObj['self-compassion'],
            'selfImage': rootObj['self-image'],
            'shortTerm': rootObj['short-term'],
            'longTerm': rootObj['long-term'],
            'sleepHabits': rootObj['sleep-habits'],
            'socialEngagement': rootObj['social-engagement'],
            'strengthTraining': rootObj['strength-training'],
            'stressResilience': rootObj['stress-resilience'],
        } 

        return emailData;
        
    }

    //execute functions
    const assessmentData = Object.fromEntries(new URLSearchParams(location.search));
    const formatData = reformatUrlData(assessmentData);
    const chartRaw = formatData.highchartsRaw;
    const wpPostData = formatData.wpRaw;
    const chartFormatted = generateChartData(chartRaw);
    let emailData = getEmailData(wpPostData);
    emailData.urlString = updateQueryStringParam( 'nodupe', null, true );
    
    if( assessmentData.hasOwnProperty('t-overall-score') ) {
        //prepare data
        
        const newDataSet = Object.assign(initialOptions,{});
        newDataSet.series[0].data = chartFormatted.data;
        newDataSet.colors = chartFormatted.colors;
        
        //UI setup
        setupUi(wpPostData);
        const chart = Highcharts.chart('highcharts-container', newDataSet);
        jQuery('#export-chart').click(function(){
            chart.exportChart();
        })
        //create data entry
        if(assessmentData.hasOwnProperty('nodupe')){
            createSubmission(wpPostData);
            updateQueryStringParam( 'nodupe', null );
        }
    }

    //interactions
    jQuery('#send-email').click(function(e){
        e.preventDefault();

        jQuery('#send-email').prop('disabled',true);
        jQuery('#send-email').text('Sending...');

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
                jQuery('#send-email').prop('disabled',false);
                jQuery('#send-email').text('Re-send email');
                jQuery('#thank-you-email').show();
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

