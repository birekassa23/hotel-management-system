// JavaScript to Calculate Profits 

const todayProfit = 1000;

function calculateProfits() {
    const CurrntProfit = todayProfit * 1;
    const weeklyProfit = todayProfit * 7;
    const monthlyProfit = todayProfit * 30;
    const yearlyProfit = todayProfit * 365;

    document.getElementById('weeklyProfit').innerText = `${weeklyProfit} ETB`;
    document.getElementById('monthlyProfit').innerText = `${monthlyProfit} ETB`;
    document.getElementById('yearlyProfit').innerText = `${yearlyProfit} ETB`;
}

// Initial calculations on page load
calculateProfits();

//  avaScript to dynamically update the profit percentage 

let profit = 60; // Example profit percentage

function updateProfitDisplay() {
    const profitCircle = document.getElementById("profitCircle");
    const profitPercentage = document.getElementById("profitPercentage");
    const performanceCard = document.getElementById("performanceCard");
    const performanceMessage = document.getElementById("performanceMessage");

    // Update circle height and profit percentage
    profitCircle.style.height = `${profit}%`;
    profitPercentage.innerText = `${profit}%`;

    // Update performance message and styles based on profit
    if (profit < 50) {
        performanceMessage.innerText = "Danger";
        performanceCard.style.backgroundColor = "#f44336"; // Red
        performanceCard.style.color = "#ffffff";
    } else if (profit >= 50 && profit < 60) {
        performanceMessage.innerText = "GOOD";
        performanceCard.style.backgroundColor = "#ffeb3b"; // Yellow
        performanceCard.style.color = "#000000";
    } else if (profit >= 60 && profit < 80) {
        performanceMessage.innerText = "VERY GOOD";
        performanceCard.style.backgroundColor = "#ff9800"; // Orange
        performanceCard.style.color = "#ffffff";
    } else {
        performanceMessage.innerText = "EXCELLENT";
        performanceCard.style.backgroundColor = "#4caf50"; // Green
        performanceCard.style.color = "#ffffff";
    }
}
// Call the function to set the initial state based on the current profit value
updateProfitDisplay();
