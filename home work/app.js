// Your web app's Firebase conf1guration
var firebaseConfig = {
apiKey: "AIzaSyCuUa4X0n HFO3xEORS5gIc Ses4EfGB4Htc",
authDomain: "form-test-c6780 . firebaseapp. com",
databaseURL: "https ://form-test-c6780.firebaseio.com",
projectId: "form-test- c6780",
storageBucket: "torm-test -C6780 . appspot.com",
messagingSenderId : "900825576170",
appId: "1:900825576170 : web : cb6f1bb9 f31e351c2f1 d93 ",
};
// Initialize Firebase
firebase.initializeApp (firebaseConfig);
// Refernece contactInfo collections
let contactInfo = firebase.database () . ref("infos ") ;
// Listen for a submit
document.querySelector (".contact -form").addEventListener ("submit",
submitForm) ;



function submitform (e) {
e.preventDefault ()

// Get input Values
let name = document.querySelector(".name") .value;
let email = document.querySelector(". email "). value;
let message = document.querySelector(". message ") . value;

saveContactInfo (name, email, message) ;

document.queryselector (" . contact-form") .reset ();

sendEmail(name,email,message);
}

//save infos to firebaseS
function saveContactInfo(name, email, message) {
    let newContactInfo = contactInfo.push();
  
    newContactInfo.set({
      name: name,
      email: email,
      message: message,
    });
  }


//sendeamil info
function sendEmail(name, email, message){
    email.send({
        Host: "smtp.gmail.com",
        Username: "ianh241203@gmail.com",
        Password:"rlrybnsuxwygeulm",
        To: "ianh241203@gmail.com",
        From:"ianh241203@gmail.com",
        Subject: `${name} sent you a message`,
        Body: ` Name: ${name} <br/> Email: ${email} <br/> Message: ${message}`, })
    .then((message)=> alert("mail sent successfully"));
}