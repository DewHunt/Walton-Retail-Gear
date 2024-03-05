// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
	apiKey: "AIzaSyBZJUHDGO9-680krYewIjRTeurrg66cSDI",
	authDomain: "retailgear-89ee0.firebaseapp.com",
	//databaseURL: "https://test-e41c2-default-rtdb.firebaseio.com",
	projectId: "retailgear-89ee0",
	storageBucket: "retailgear-89ee0.appspot.com",
	messagingSenderId: "198099682584",
	appId: "1:198099682584:web:4319de0585a25515b04a6d",
});


// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);

    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };

    return self.registration.showNotification(
        title,
        options,
    );
});
