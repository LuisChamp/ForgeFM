#!/bin/bash

# Limpiar iptables
iptables -F
iptables -t nat -F

# Politicas a DROP
iptables -P INPUT DROP
iptables -P OUTPUT DROP
iptables -P FORWARD DROP

# Permitimos localhost en el router
iptables -A INPUT -i lo -j ACCEPT
iptables -A OUTPUT -o lo -j ACCEPT

# Permitir al router acceder a los sitios web
iptables -A OUTPUT -d 192.168.10.100 -p tcp --match multiport --dports 80,443 -j ACCEPT
iptables -A INPUT -s 192.168.10.100 -p tcp --match multiport --sports 80,443 -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir al router hacer consultas al DNS
iptables -A OUTPUT -d 192.168.10.101 -p udp --dport 53 -j ACCEPT
iptables -A INPUT -s 192.168.10.101 -p udp --sport 53 -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir al router salir a internet
iptables -A OUTPUT -o enp0s3 -j ACCEPT
iptables -A INPUT -i enp0s3 -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir comunicacion del router con los servidores
iptables -A OUTPUT -o enp0s8 -d 192.168.10.0/24 -p icmp -j ACCEPT
iptables -A INPUT -i enp0s8 -s 192.168.10.0/24 -p icmp -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir comunicacion de los servidores con el router
iptables -A INPUT -i enp0s8 -s 192.168.10.0/24 -p icmp -j ACCEPT
iptables -A OUTPUT -o enp0s8 -d 192.168.10.0/24 -p icmp -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir comunicacion del router con los equipos de la red interna
iptables -A OUTPUT -o enp0s9 -d 192.168.11.0/24 -p icmp -j ACCEPT
iptables -A INPUT -i enp0s9 -s 192.168.11.0/24 -p icmp -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir comunicacion de los equipos de la red interna con el router
iptables -A INPUT -i enp0s9 -s 192.168.11.0/24 -p icmp -j ACCEPT
iptables -A OUTPUT -o enp0s9 -d 192.168.11.0/24 -p icmp -m state --state ESTABLISHED,RELATED -j ACCEPT

# Redireccion de las consultas DNS de equipos exteriores al servidor DNS
iptables -t nat -A PREROUTING -i enp0s3 ! -d 192.168.10.101/24 -p tcp --dport 53 -j DNAT --to-destination 192.168.10.101:53
iptables -t nat -A PREROUTING -i enp0s3 ! -d 192.168.10.101/24 -p udp --dport 53 -j DNAT --to-destination 192.168.10.101:53

# Permitir equipos de la red interna consultar al servidor DNS
iptables -A FORWARD -s 192.168.11.0/24 -d 192.168.10.101 -p udp --dport 53 -j ACCEPT
iptables -A FORWARD -s 192.168.10.101 -d 192.168.11.0/24 -p udp --sport 53 -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir a los equipos del exterior consultar al servidor dns
iptables -A FORWARD -d 192.168.10.101 -i enp0s3 -p udp --dport 53 -j ACCEPT
iptables -A FORWARD -s 192.168.10.101 -o enp0s3 -p udp --sport 53 -m state --state ESTABLISHED,RELATED -j ACCEPT

iptables -A FORWARD -d 192.168.10.101 -i enp0s3 -p tcp --dport 53 -j ACCEPT
iptables -A FORWARD -s 192.168.10.101 -o enp0s3 -p tcp --sport 53 -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir servidor DNS consultar a sus reenviadores (consulta y respuesta)
iptables -A FORWARD -s 192.168.10.101 -d 8.8.4.4 -p udp --dport 53 -j ACCEPT
iptables -A FORWARD -s 8.8.4.4 -d 192.168.10.101 -p udp --sport 53 -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -A FORWARD -s 192.168.10.101 -d 8.8.8.8 -p udp --dport 53 -j ACCEPT
iptables -A FORWARD -s 8.8.8.8 -d 192.168.10.101 -p udp --sport 53 -m state --state ESTABLISHED,RELATED -j ACCEPT

# Redireccion del exterior hacia el servidor web por los puerto 80 y 443
iptables -t nat -A PREROUTING -i enp0s3 -p tcp --dport 80 -j DNAT --to-destination 192.168.10.100:80
iptables -t nat -A PREROUTING -i enp0s3 -p tcp --dport 443 -j DNAT --to-destination 192.168.10.100:443

# Permitir a cualquier equipo poder acceder al sitio del servidor web
iptables -A FORWARD -d 192.168.10.100 -p tcp --match multiport --dports 80,443 -j ACCEPT
iptables -A FORWARD -s 192.168.10.100 -p tcp --match multiport --sports 80,443 -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir equipos y servidores salir de la red con enmascaramiento
iptables -t nat -A POSTROUTING -o enp0s3 -j MASQUERADE
iptables -A FORWARD -s 192.168.10.0/23 -o enp0s3 -j ACCEPT
iptables -A FORWARD -d 192.168.10.0/23 -i enp0s3 -m state --state ESTABLISHED,RELATED -j ACCEPT

