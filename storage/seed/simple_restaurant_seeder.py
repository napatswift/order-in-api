import os
from time import sleep
from bs4 import BeautifulSoup as bs
import json
import requests
import numpy as np


base_url = 'http://localhost/api'
images = ['assets/' + ats for ats in os.listdir('assets') if ats.endswith('.jpg')]

manager_req_data = {'username': 'kavon.parisian', 'password': 'password'}
response = requests.post(base_url+'/auth/login', data=manager_req_data)
if response.status_code != 200:
    exit()
manager_access_token = response.json()['access_token']
manger_headers = {
    'Authorization': 'Bearer '+manager_access_token,
    'Accept': 'application/json'
}
print('manger logged in')

data_menu = json.load(open('./delivery-menu.json'))
print(data_menu.keys())

menu_groups = data_menu['menuGroups']
print(len(menu_groups), 'menu groups')
print('-'*30)

def add_category(cat_name, imagefname):
    # add category
    req_data = {'name': cat_name,}
    with open(imagefname, 'rb') as img:
        response = requests.post(
            base_url+'/categories',
            data=req_data,files={'image': img},
            headers=manger_headers
        )

    if response.status_code != 201:
        return

    return response.json()['category']

for menu_group in menu_groups:
    print(menu_group['name'])

    cat_id = 1#add_category(menu_group['name'], )

    items = menu_group['items']
    for item in items:
        image_fpath = []
        if 'photo' in item:
            photo_id = item['photo']['photoId']
            image_fpath = [fname for fname in images if photo_id in fname]
        
        if not image_fpath: continue

        food_data_req = {
            'food_name': item['name'],
            'food_price': int(item['price']['exact']),
            'food_detail': '' if 'description' not in item.keys() else item['description'],
            'category_ids[0]': cat_id,
            'cooking_time': np.random.randint(3, 15),
        }

        with open(image_fpath[0], 'rb') as img:
            response = requests.post(
                base_url+'/foods',
                data=food_data_req,
                files={'image': img},
                headers=manger_headers)
        
        print(response.json())
        sleep(1)
