#!/bin/bash

# Limpiar iptables
iptables -F
iptables -t nat -F

# Politicas generales
iptables -P INPUT DROP
iptables -P OUTPUT DROP
iptables -P FORWARD DROP

# Permitimos al localhost
iptables -A INPUT -i lo -j ACCEPT
iptables -A OUTPUT -o lo -j ACCEPT

# Permitir al servidor hacer ping a los equipos de ambas redes
iptables -A OUTPUT -d 192.168.10.0/23 -p icmp -j ACCEPT
iptables -A INPUT -s 192.168.10.0/23 -p icmp -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir a los equipos de ambas redes hacer ping al servidor
iptables -A INPUT -s 192.168.10.0/23 -p icmp -j ACCEPT
iptables -A OUTPUT -d 192.168.10.0/23 -p icmp -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitimos que el DNS pueda consultar a los reenviadores
iptables -A OUTPUT -d 8.8.8.8 -p udp --dport 53 -j ACCEPT
iptables -A INPUT -s 8.8.8.8 -p udp --sport 53 -m state --state ESTABLISHED,RELATED -j ACCEPT

iptables -A OUTPUT -d 8.8.4.4 -p udp --dport 53 -j ACCEPT
iptables -A INPUT -s 8.8.4.4 -p udp --sport 53 -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitimos a la red interna poder consultar al DNS
iptables -A INPUT -s 192.168.10.0/23 -p udp --dport 53 -j ACCEPT
iptables -A OUTPUT -d 192.168.10.0/23 -p udp --sport 53 -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitimos a los equipos exteriores poder consultar al dns
iptables -A INPUT -p udp --dport 53 -j ACCEPT
iptables -A OUTPUT -p udp --sport 53 -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitimos al equipo poder acceder a los sitios web
iptables -A OUTPUT -p tcp --match multiport --dports 80,443 -j ACCEPT
iptables -A INPUT -p tcp --match multiport --sports 80,443 -m state --state ESTABLISHED,RELATED -j ACCEPT
