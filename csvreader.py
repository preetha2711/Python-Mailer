import smtplib
import csv

with open('tester.csv') as csvfile:
    read = csv.reader(csvfile, delimiter = ',')
    for row in read:
        content = "Hey!"+"\n"+ " Welcome to the Cryptic Hunt!  Your user name is " + row[1] + ", and your password is " + row[2] + "\n" + "Please feel free to message us on the CSGS Facebook page for queries!" + "\n" " The hunt can be accessed at : at precisely 22:00 aka 4 hours from now!" + "\n" + " Have fun and happy hunting!"+"\n"+ " Love Team WiCS and Team CSGS"
        receiver = row[0]
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


    



    
