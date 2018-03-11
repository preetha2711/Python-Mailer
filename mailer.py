import smtplib

#tester
file = open("Biology.txt","r")
for line in file:
    content = line
    receiver = 'malavika.murali_ug19@ashoka.edu.in'
    email = 'legitashoka@gmail.com'
    password = ''




    mail = smtplib.SMTP('smtp.gmail.com', 587)

    mail.ehlo()

    mail.starttls()
    mail.login(email,password)
    fromemail = email
    mail.sendmail(fromemail,receiver,content)

    mail.close()
