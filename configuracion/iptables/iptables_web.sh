#!/bin/bash

# Limpiar iptables
iptables -F
iptables -t nat -F

# Politicas a DROP
iptables -P INPUT DROP
iptables -P OUTPUT DROP
iptables -P FORWARD DROP

# Permitimos localhost
iptables -A INPUT -i lo -j ACCEPT
iptables -A OUTPUT -o lo -j ACCEPT

# Permitir al servidor hacer ping a equipos de ambas redes
iptables -A OUTPUT -d 192.168.10.0/23 -p icmp -j ACCEPT
iptables -A INPUT -s 192.168.10.0/23 -p icmp -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir a los equipos de ambas redes hacer ping al servidor
iptables -A INPUT -s 192.168.10.0/23 -p icmp -j ACCEPT
iptables -A OUTPUT -d 192.168.10.0/23 -p icmp -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir poder acceder al sitio desde cualquier equipo
iptables -A INPUT -p tcp --match multiport --dports 80,443 -j ACCEPT
iptables -A OUTPUT -p tcp --match multiport --sports 80,443 -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir al servidor acceder al sitio y conectarse al servidor mariadb
iptables -A OUTPUT -p tcp --match multiport --dports 80,443,3306 -j ACCEPT
iptables -A INPUT -p tcp --match multiport --sports 80,443,3306 -m state --state ESTABLISHED,RELATED -j ACCEPT

# Permitir al servidor poder hacer consultas al servidor DNS
iptables -A OUTPUT -d 192.168.10.101 -p udp --dport 53 -j ACCEPT
iptables -A INPUT -s 192.168.10.101 -p udp --sport 53 -m state --state ESTABLISHED,RELATED -j ACCEPT

