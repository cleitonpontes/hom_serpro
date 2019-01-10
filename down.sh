#!/bin/bash

echo Fechando container da aplicação
sudo docker-compose down

echo Eliminando imagens
sudo docker rmi -f 59c542ceb57a
sudo docker rmi -f 4d73a90f5eef

echo Eliminando bando de dados
sudo docker volume rm aguconta_conta-postgres-data
