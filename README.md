for 2factor auth with otp

we are here trying to use fortify two factors authentication to send the same code to email or sms

files used in this section :

user model

app.actions.createnewuser

app.actions.updateuserprofileinformation

app.Listners.SendTwoFactorCodeLisner

eventserviceprovider

notifications.sendOTP

app.fortify.TwoFactor.generateOTP

config.fortify

.env

register.vue
///////////////////////////////////////////////////


for socialite follow the tutorial from designated coder
