const labels = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12']

const data = {
    labels: labels, 
    datasets:[
        {
            label:'Doanh số',
            backgroundColor: "blue",
            borderColor:"blue",
            data:[2, 23, 56, 32, 23, 45, 67, 2, 56, 65, 23, 67],
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

