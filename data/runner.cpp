#include <cstdio>
#include <cstdlib>
#include <cassert>

#include <iostream>
#include <fstream>
#include <sstream>
#include <string>

using namespace std;

const int _fail = 1;
const int TLcode = 124;

string toString(double value)
{
   stringstream s;
   s << value;
   return s.str();
}

int getTimeLimit(const string &problemPath)
{
   ifstream st((problemPath + "/timelimit").c_str());
   if (!st.is_open())
   {
      cerr << "Cannot fetch timelimit for problem" << endl;
      exit(_fail);
   }
   int timelimit;
   st >> timelimit;
   st.close();
   return timelimit;
}

int getInvocationTermCode()
{
   ifstream st("./sandbox/invocationTermCode");
   if (!st.is_open())
   {
      cerr << "Cannot fetch invocation termination code" << endl;
      exit(_fail);
   }
   int code;
   st >> code;
   st.close();
   return code;
}

void sumUpTime()
{
   ifstream st("./sandbox/resourceConsumption");
   if (!st.is_open())
   {
      cerr << "Cannot fetch invocation time" << endl;
      exit(_fail);
   }
   double t1, t2;
   int mem;
   st >> t1 >> t2 >> mem;
   st.close();
   ofstream ost("./sandbox/resourceConsumption");
   ost.precision(4);
   ost << t1 + t2 << " " << mem / 4 << endl;
   ost.close();
}

double getInvocationTime()
{
   ifstream st("./sandbox/resourceConsumption");
   if (!st.is_open())
   {
      cerr << "Cannot fetch invocation time" << endl;
      exit(_fail);
   }
   double time;
   st >> time;
   st.close();
   return time;
}

string vname[] = {"ok", "tl", "re"};
int verdict;

string getRunCmd()
{
	ifstream in("./sandbox/language");
	string lang, ans;
	if ((in >> lang) && lang == "java") 
		ans = "java -cp ./sandbox/ -Xmx512M -Xss64M -DONLINE_JUDGE=true -Duser.language=en -Duser.region=US -Duser.variant=US Main";
	else 
		ans = "./sandbox/main";
	in.close();
	return ans;
}

int main(int argc, char** argv)
{
   assert(argc == 2);
   double timelimit = getTimeLimit(argv[1]) / 1000.0;
   string cmd = "timeout --kill-after=0s " + toString(1.0 * timelimit + 0.5) + "s" + 
             " time --output=./sandbox/resourceConsumption --format \"%U %S %M\" " + 
             getRunCmd() + " <./sandbox/test.in >./sandbox/test.out; " +
             "echo $? >./sandbox/invocationTermCode";
   system(cmd.c_str());
   sumUpTime();
   int status = getInvocationTermCode();
   const double eps = 1e-6;
   if (status == TLcode || getInvocationTime() > timelimit + eps)
   {
      system(cmd.c_str());
      sumUpTime();
      status = getInvocationTermCode();
      if (status == TLcode || getInvocationTime() > timelimit + eps) verdict = 1;
      else verdict = status ? 2 : 0;
   }
   else verdict = status ? 2 : 0;
   cout << verdict << endl;
   return 0;
}
