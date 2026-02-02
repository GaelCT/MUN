import smtplib

server = smtplib.SMTP('smtp.gmail.com', 587)
server.starttls()
server.login('ilovebigmacs.org@gmail.com', 'Lego5312!')
print("Logged in successfully")
server.quit()