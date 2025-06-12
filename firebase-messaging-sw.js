// Import Firebase scripts for compatibility version
importScripts('https://www.gstatic.com/firebasejs/10.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.0.0/firebase-messaging-compat.js');

// Firebase configuration
firebase.initializeApp({
    apiKey: "AIzaSyBHoHqIHSYXyZpcsS5Gw6HGa5bYK-ktpFw",
    authDomain: "ecommerce-store-52bb1.firebaseapp.com",
    projectId: "ecommerce-store-52bb1",
    storageBucket: "ecommerce-store-52bb1.appspot.com", // Corrected here
    messagingSenderId: "81418308198",
    appId: "1:81418308198:web:885689707e54ecbd613ba7",
    measurementId: "G-THBCZS5BCH"
});

// Retrieve Firebase Messaging instance
const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message:', payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/icon.png' // Make sure this icon exists at root level
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});
