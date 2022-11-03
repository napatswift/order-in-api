import os
import random
import re
from time import sleep
from bs4 import BeautifulSoup as bs
import json
import requests
import numpy as np


base_url = 'http://localhost/api'
images = ['assets/' + ats for ats in os.listdir('assets') if ats.endswith('.jpg')]
user_agents = [
  "Mozilla/5.0 (Windows NT 10.0; rv:91.0) Gecko/20100101 Firefox/91.0",
  "Mozilla/5.0 (Windows NT 10.0; rv:78.0) Gecko/20100101 Firefox/78.0",
  "Mozilla/5.0 (X11; Linux x86_64; rv:95.0) Gecko/20100101 Firefox/95.0",
  "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36"
  ]

manager_req_data = {'username': 'kprosacco', 'password': 'password'}
response = requests.post(base_url+'/auth/login', data=manager_req_data)
if response.status_code != 200:
    exit()
manager_access_token = response.json()['access_token']
manger_headers = {
    'Authorization': 'Bearer '+manager_access_token,
    'Accept': 'application/json',
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
        print(response.json())
        return

    return response.json()['category']

for menu_group in menu_groups:
    print(menu_group['name'])

    cat_id = add_category(menu_group['name'], random.choice(images))

    items = menu_group['items']
    for item in items:
        if 'photo' not in item.keys(): continue

        image_fpath = []
        if 'photo' in item:
            photo_id = item['photo']['photoId']
            image_fpath = [fname for fname in images if photo_id in fname]
        
        if not image_fpath:
            url = item['photo']['largeUrl']
            img_path = 'assets/'+re.sub('.*/', '', url)
            with open(img_path, 'wb') as fp:
                print('get "{}"'.format(url))
                response = requests.get(url, headers={
                    'User-Agent': random.choice(user_agents)
                })
                if response.status_code != 200:
                    print(response)
                    continue
                fp.write(response.content)
            image_fpath = [img_path]

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
