import smtplib
import csv

with open('trial.csv') as csvfile:
    read = csv.reader(csvfile, delimiter = ',')
    print (read)

    for row in read:
        print (row[1]), (row[2]), row[3]
'''
#tester
file = open("Biology.txt","r")
for line in file:
    content = line
    receiver = 'tester_ug19@ashoka.edu.in'
    email = 'ashokalegit@gmail.com'
    password = 'revenge102'




    mail = smtplib.SMTP('smtp.gmail.com', 587)

    mail.ehlo()

    mail.starttls()
    mail.login(email,password)
    fromemail = email
    mail.sendmail(fromemail,receiver,content)
    mail.close()
'''