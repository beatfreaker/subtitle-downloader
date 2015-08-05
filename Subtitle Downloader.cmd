@echo off
cls
:loop
IF %1=="" GOTO completed
  php c:\subtitle-downloader.php %1
  exit
  GOTO loop
:completed
