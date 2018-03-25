import smtplib
import csv

with open('trial.csv') as csvfile:
    read = csv.reader(csvfile, delimiter = ',')
    print (read)

    for row in read:
        content = "Your user name is " + row[2] + ", and your password is " + row[3]
        receiver = row[1]
        email = 'ashokalegit@gmail.com'
        password = 'revenge102'
        mail = smtplib.SMTP('smtp.gmail.com', 587)
        mail.ehlo()
        mail.starttls()
        mail.login(email,password)
        fromemail = email
        mail.sendmail(fromemail,receiver,content)
        mail.close()

        #tester


    



    
