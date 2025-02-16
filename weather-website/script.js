const API_KEY = '2036940a90b69ff383ea1ff0a96c541a'; // Replace with your API key
const CITY = 'Gurgaon'; // Replace with your city
let selectedLanguage = 'en-US'; // Default language

// Language Mapping
const languageMap = {
    'en-US': "English",
    'hi-IN': "हिन्दी",
    'te-IN': "తెలుగు",
    'ta-IN': "தமிழ்",
    'bn-IN': "বাংলা",
    'mr-IN': "मराठी"
};

// Fetch Weather Data
async function fetchWeather() {
    try {
        const response = await fetch(`https://api.openweathermap.org/data/2.5/weather?q=${CITY}&units=metric&appid=${API_KEY}`);
        const data = await response.json();

        if (response.ok) {
            updateWeatherUI(data);
            checkWeatherAlerts(data);
            saveWeatherOffline(data);
        } else {
            throw new Error(data.message || "Failed to fetch weather data.");
        }
    } catch (error) {
        console.error("Error fetching weather:", error);
        loadWeatherOffline();
    }
}

// Update UI
function updateWeatherUI(data) {
    document.getElementById('location').innerText = data.name;
    document.getElementById('temp').innerText = `${data.main.temp}°C`;
    document.getElementById('wind').innerText = `${data.wind.speed} km/h`;
    document.getElementById('humidity').innerText = `${data.main.humidity}%`;
    document.getElementById('condition').innerText = data.weather[0].description;
}

// Save for Offline Use
function saveWeatherOffline(data) {
    localStorage.setItem('weatherData', JSON.stringify(data));
}

// Load Offline Weather
function loadWeatherOffline() {
    const storedData = localStorage.getItem('weatherData');
    if (storedData) {
        updateWeatherUI(JSON.parse(storedData));
    } else {
        alert("No internet & no saved weather data.");
    }
}

// Read Weather Aloud
function readWeatherAloud() {
    // Stop any ongoing speech first
    window.speechSynthesis.cancel();
    
    let weatherText = generateWeatherText();
    
    const speech = new SpeechSynthesisUtterance(weatherText);
    speech.lang = selectedLanguage;
    speech.rate = 1;
    speech.pitch = 1;
    
    window.speechSynthesis.speak(speech);
}
// Generate Weather Text
function generateWeatherText() {
    const conditionElem = document.getElementById('condition');
    const tempElem = document.getElementById('temp');
    const windElem = document.getElementById('wind');

    let condition = conditionElem ? conditionElem.innerText : "clear sky";
    let temperature = tempElem ? tempElem.innerText : "no data";
    let windSpeed = windElem ? windElem.innerText : "0 km/h";

    let warning = condition.includes("rain") || condition.includes("storm") 
        ? "It's not good for farming today. Please take precautions." 
        : "The weather is clear. Good to go for farming.";

    return `The current weather is ${condition}. Temperature is ${temperature}. Wind speed is ${windSpeed}. ${warning}`;
}

// Language Change Handler
function changeLanguage(event) {
    selectedLanguage = event.target.value;
}

// Dark Mode Toggle
function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");
}

// Load Weather on Page Load
fetchWeather();

// Attach event listener to button
document.getElementById("speakWeather").addEventListener("click", readWeatherAloud);

function speakWeather(language = 'en') {
    // Stop any ongoing speech first
    window.speechSynthesis.cancel();

    const location = document.getElementById("location").innerText;
    const temp = document.getElementById("temp").innerText;
    const wind = document.getElementById("wind").innerText;
    const humidity = document.getElementById("humidity").innerText;
    const condition = document.getElementById("condition").innerText;

    const speech = new SpeechSynthesisUtterance();
    
    if (language === 'मौसम') {
        speech.text = `वर्तमान मौसम ${location} में ${temp} डिग्री सेल्सियस है। मौसम ${condition} है। हवा की गति ${wind} किलोमीटर प्रति घंटा है और नमी ${humidity} प्रतिशत है।`;
    } else {
        speech.text = `The current weather in ${location} is ${temp} degrees Celsius with ${condition}. Wind speed is ${wind} kilometers per hour and humidity is ${humidity} percent.`;
    }
    
    window.speechSynthesis.speak(speech);
}

function startListening() {
    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.lang = "en-US";
    recognition.start();

    recognition.onresult = function(event) {
        const speechResult = event.results[0][0].transcript.toLowerCase();
        console.log("User said:", speechResult);

        if (speechResult.includes("मौसम") || speechResult.includes("weather")) {
            if (speechResult.includes("हिंदी")) {
                speakWeather('hi');
            } else {
                speakWeather('en');
            }
        } else {
            const response = new SpeechSynthesisUtterance("Sorry, I didn't understand that.");
            window.speechSynthesis.speak(response);
        }
    };

    recognition.onerror = function(event) {
        console.error("Speech recognition error:", event.error);
    };
}
