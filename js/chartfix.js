const labels = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12']

const data = {
    labels: labels, 
    datasets:[
        {
            label:'Doanh số',
            backgroundColor: "blue",
            borderColor:"blue",
            data:[49900000, 23519920, 27999960, 17790300, 29970000, 14000000, 16999950, 25974000, 17401800 , 23424000 , 22949955 , 70765000],
            tension: 0.4, 

        },
    ],
}
const config = {
    type:'line',
    data:data, 
    border:data,
}
const canvas = document.getElementById('canvas');
const chart = new Chart(canvas, config)

