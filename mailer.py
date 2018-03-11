import smtplib

#tester
content = "python mailer test"
receiver = 'reeju.datta@gmail.com'
email = 'datta.preetha@gmail.com'
password = '' #insert password here



for i in range(0,10):
    mail = smtplib.SMTP('smtp.gmail.com', 587)

    mail.ehlo()

    mail.starttls()
    mail.login(email,password)
    fromemail = email
    mail.sendmail(fromemail,receiver,content)

    mail.close()
