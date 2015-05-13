#include <unistd.h>

#include <ctime>
#include <cstdio>
#include <cstdlib>

#include <algorithm>
#include <iostream>
#include <fstream>

using namespace std;

const int _fail = 1;

string itos(int a)
{
   string res = "";
   if (a == 0) return "0";
   while (a)
   {
      res += char(a % 10 + '0');
      a /= 10;
   }
   reverse(res.begin(), res.end());
   return res;
}

int getFirstUntestedSubmission()
{
   ifstream f("tested");
   if (!f.is_open())
   {
      cerr << "IO FAIL: Cannot fetch untested submission from 'tested' file" << endl;
      exit(_fail);
   }
   int first;
   f >> first;
   f.close();
   return first;
}

void printTestedSubmission(int id)
{
   ofstream f("tested");
   if (!f.is_open())
   {
      cerr << "IO FAIL: Cannot write tested submission to 'tested' file" << endl;
      exit(_fail);
   }
   f << id << endl;
   f.close();
}

int main()
{
   ifstream lng, prb;
   for (int id = getFirstUntestedSubmission(); ; id++)
   {
      string fname = "submissions/" + itos(id) + ".code";
      cerr << "Waiting for submission " << fname << endl;
      ifstream codeFile;
      for (codeFile.open(fname.c_str()); !codeFile.is_open();)
      {
         usleep(1000000);
         codeFile.open(fname.c_str());
      }
      codeFile.close();
      cerr << "Testing submission " << fname << endl;
      if (system(("./judge " + itos(id)).c_str()))
      {
         cerr << "CHECK FAILED. Who cares?" << endl;
         //return _fail;
      }
      printTestedSubmission(id + 1);
   }    
}
