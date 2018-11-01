# -*- coding: utf-8 -*-

##########
#使い方 python sqlinsert.py(このスクリプトファイル名) toukousya(投稿者の名前) target.jpg(アップロードする写真のパス)

import mysql.connector
import boto3
import sys

args = sys.argv


def aws_face_recog(source, target):
    client = boto3.client('rekognition', region_name="ap-northeast-1")
    detect_flag = False

    imageSource = open(source, 'rb')
    imageTarget = open(target, 'rb')

    response = client.compare_faces(SimilarityThreshold=70,
                                    SourceImage={'Bytes': imageSource.read()},
                                    TargetImage={'Bytes': imageTarget.read()})

    for faceMatch in response['FaceMatches']:
        position = faceMatch['Face']['BoundingBox']
        confidence = str(faceMatch['Face']['Confidence'])
        # print('The face at ' +
        #       str(position['Left']) + ' ' +
        #       str(position['Top']) +
        #       ' matches with ' + confidence + '% confidence')
        detect_flag = True
    imageSource.close()
    imageTarget.close()
    return detect_flag


conn = mysql.connector.connect(
    host='localhost',
    port=3306,
    user='root',
    password='',
    database='2018_docomo_hackathon',
)

connected = conn.is_connected()
#print(connected)
if not connected:
    conn.ping(True)

cur = conn.cursor()
cur.execute("SELECT img_path,name FROM 2018_docomo_hackathon.user_img_info")

# table = cur.fetchall()
# print(table)

# print(cur.statement)
while True:
    string = cur.fetchone()
    #print("hai:", string)
    if string is None:
        break
    else:
        img_path, name = string[0], string[1]
        # print("target:" + img_path + ", photographer:" + name)
        # print(img_path, args[2])
        if aws_face_recog(img_path, args[2]):  # SQLで取得したパスをソースに、第二引数のターゲットを解析
            # print("検出")
            sql = "INSERT INTO 2018_docomo_hackathon.img_info(img_path,photographer,subject) values('{img_path}','{photog}','{sub}');".format(
                img_path=args[2], photog=args[1], sub=name);
            #print(sql)
            # print("↑のSQL実行した")
            cur.execute(sql)
            conn.commit()
cur.close()
conn.close()
#print("fin.")