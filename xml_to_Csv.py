# -*- coding: utf-8 -*-
"""
Created on Wed Apr  4 10:37:37 2018

@author: Luis
"""
#%% Importa a base em xml e dar o parse.

import xml.etree.ElementTree as ET
import pandas as pd
tree = ET.parse('base_museuIndio.xml') #Nome do documento xml, deve estar na mesma pasta do script.
root = tree.getroot()

lista_tags = []
dict_base = {}

#%%Ler os elementos da base

#Pega todas as tags/colunas do txt e coloca em uma lista.
for element in root:
    for tag in element:
        lista_tags.append(tag.tag)
lista_tags = set(lista_tags) #Tira a repetição das tags/colunas

#Cria listas para cada tag em um dicionário.
for tag in lista_tags:
    dict_base[tag] = []
  
#Lê os valores das tags/colunas do txt e armazena nas respectivas lista no dicionário.
for element in root:
    for column in lista_tags:
        for tag in element.findall(column):
            dict_base[column].append(tag.text)

#%%Exportação
            
#Transforma o dicionário em um DataFrame para ser exportado para csv
base_csv = pd.DataFrame.from_dict(dict_base, orient='index') #Se o xml for padronizado retirar o "orient="index""

#Exporta o dataframe para csv (Neste caso o dataframe não é padronizado, por isso foi usado o "transpose", caso contrário, removê-lo).
base_csv.transpose().to_csv('base_resultante.csv', encoding='utf-8')
