#include <ctime>
#include <cstdio>
#include <cstdlib>
#include <cassert>

#include <algorithm>
#include <iostream>
#include <fstream>

using namespace std;

const int _fail = 1;
const int _fail_to_run = 2;
const int _fail_to_check = 3;

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

int compileCppCode(int id)
{
   if (system(("cp ./submissions/" + itos(id) + ".code ./sandbox/main.cpp").c_str()))
   {
      cerr << "Unable to copy file to sandbox" << endl;
      exit(_fail);
   }
   string cmd = "g++ ./sandbox/main.cpp -o ./sandbox/main "
            "-static -fno-optimize-sibling-calls -fno-strict-aliasing "
            "-DONLINE_JUDGE -lm -s -x c++ -O2 "
            "2>./submissions/" + itos(id) + ".compilationReport";
   int res = system(cmd.c_str());
   system("rm -f ./sandbox/main.cpp");
   return res;
}

int compilePascalCode(int id)
{
   if (system(("cp ./submissions/" + itos(id) + ".code ./sandbox/main.pas").c_str()))
   {
      cerr << "Unable to copy file to sandbox" << endl;
      exit(_fail);
   }
   string cmd = "fpc -dOLINE_JUDGE -So -XS -O2 ./sandbox/main.pas "
              "> ./submissions/" + itos(id) + ".compilationReport 2>&1 ";
   int res = system(cmd.c_str());
   system("rm -f ./sandbox/main.pas");
   return res;
}

int compileJavaCode(int id)
{
	if (system(("cp ./submissions/" + itos(id) + ".code ./sandbox/Main.java").c_str()))
	{
		cerr << "Unable to copy file to sandbox" << endl;
		exit(_fail);
	}
	system(("cp ./submissions/" + itos(id) + ".language ./sandbox/language").c_str());
	string cmd = "javac -cp \".;*\" ./sandbox/Main.java "
				 "> ./submissions/" + itos(id) + ".compilationReport 2>&1 ";
	int res = system(cmd.c_str());
	system("rm -f ./sandbox/Main.java");
	return res;
}

void updateStatus(int id, const string &status)
{
   ofstream st(("./submissions/" + itos(id) + ".status").c_str());
   if (!st.is_open())
   {
      cerr << "Unable to update status of submission" << endl;
      exit(_fail);
   }
   st << status << endl;
   st.close();
}

string getSubmissionLanguage(int id)
{
   ifstream st(("./submissions/" + itos(id) + ".language").c_str());
   if (!st.is_open())
   {
      cerr << "Unable to fetch language of submission" << endl;
      exit(_fail);
   }
   string lang;
   st >> lang;
   st.close();
   return lang;
}

string getSubmissionProblem(int id)
{
   ifstream st(("./submissions/" + itos(id) + ".problem").c_str());
   if (!st.is_open())
   {
      cerr << "Unable to fetch submission's problem" << endl;
      exit(_fail);
   }
   string prob;
   st >> prob;
   st.close();
   return prob;
}

int getTesterExitCode()
{
   ifstream st("./sandbox/testerExitCode");
   if (!st.is_open())
   {
      cerr << "Unable to fetch submission's problem" << endl;
      exit(_fail);
   }
   int code;
   st >> code;
   st.close();
   return code;
}

int main(int argc, char** argv)
{
   assert(argc == 2);
   int id = atoi(argv[1]);
   cerr << "Testing started. SubmissionId = " << id << endl;
   system(("rm ./submissions/" + string(argv[1]) + ".score").c_str());
   updateStatus(id, "testing");

   string lang = getSubmissionLanguage(id);
   string problem = getSubmissionProblem(id);
   cerr << "Problem = " << problem << endl;
   cerr << "Language = " << lang << endl;

   int error = 0;
   if (lang == "pascal") error = compilePascalCode(id);
   else if (lang == "cpp") error = compileCppCode(id);
   else if (lang == "java") error = compileJavaCode(id);
   else
   {
      cerr << "Unknown language" << endl;
      return _fail;
   }

   if (!error)
   {         
      system(("./tester tasks/" + problem + " >./sandbox/currentResult; echo $? >./sandbox/testerExitCode").c_str());
      int res = getTesterExitCode();
      if (res == _fail || res == _fail_to_run)
      {
         updateStatus(id, "FAIL");
         cerr << "Fatal error occured while running. Abort testing." << endl;
         return _fail;
      }
      if (res == _fail_to_check)
      {
         updateStatus(id, "FAIL");
         cerr << "Fatal error occured while checking. Abort testing." << endl;
         return _fail;
      }
      cerr << "Submission successfully tested" << endl;
      if (system(("tasks/" + problem + "/grade " + itos(id) + " tasks/" + problem + " <./sandbox/currentResult").c_str()))
      {
         updateStatus(id, "FAIL");
         cerr << "Cannot grade submission. Abort testing." << endl;
         return _fail;
      }
      cerr << "Submission successfully graded" << endl;
      cerr << "Testing completed" << endl;
      updateStatus(id, "tested");
      //system("rm -f ./sandbox/currentResult");
      system("rm ./sandbox/language");
   } 
   else 
   {
      updateStatus(id, "CE");
      cerr << "Compilation error" << endl;
      system(("echo 0 >./submissions/" + itos(id) + ".score").c_str());
   }
   return 0;
}
