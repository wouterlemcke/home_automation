# -*- coding: iso-8859-15 -*-
import MySQLdb
import serial
import subprocess

#db = MySQLdb.connect(host="localhost", # your host, usually localhost
#                     user="zwave", # your username
#                      passwd="Panda50!", # your password
#                      db="zwave") # name of the data base

#cur = db.cursor() 

ser = serial.Serial('/dev/ttyUSB0', 9600)

#Loop to execute everytime data from the arduino comes in
while 1 :
    inString = ser.readline();
    print inString.rstrip();
    subprocess.call(["php", "/home/pi/sensorlogger/arduinoReceiver.php", "100" , inString.split('|')[0].rstrip()]);
    subprocess.call(["php", "/home/pi/sensorlogger/arduinoReceiver.php", "101" , inString.split('|')[1].rstrip()]);



    #temperature
    #cur.execute("insert into sensor_data (sd_s_id, sd_datetime, sd_value) values ('100',NOW(),%s)",inString.split('|')[1].rstrip())
    #humidity
    #cur.execute("insert into sensor_data (sd_s_id, sd_datetime, sd_value) values ('101',NOW(),%s)",inString.split('|')[0])
    #db.commit()




