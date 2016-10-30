import csv
import os
import re

# Preprocessing data from CUE-_ to BIO
def preprocess_bio():
  # Read in all training files
  for filename in os.listdir("train"):
    if filename != ".DS_Store":
      with open("train/"+filename, 'r+') as f:
        print("train/"+filename)
        old = f.readlines()
        f.seek(0)
        f.truncate()
        fline = re.split('\t+', old[0])
        prev = fline[2]
        # Convert cues to BIO tags
        if "CUE" in prev:
          prev = "B"
        else:
          prev = "O"
        f.write(fline[0] + "\t" + fline[1] + "\t" + prev + "\n")
        lines = old[1:]
        for line in lines:
          lineSplit = re.split('\t+', line)
          if (len(lineSplit) < 3):
            # f.write("\n")
            continue
          if "CUE" in lineSplit[2]:
            if (prev == "B" or prev == "I"):
              lineSplit[2] = "I"
              prev = "I"
            else:
              lineSplit[2] = "B"
              prev = "B"
          else:
            lineSplit[2] = "O"
            prev = "O"
          f.write("\t".join(lineSplit) + "\n")
      f.close()

# Read in from uncertainty file and create a list of uncertainty words
def preprocess_csv():
  uncert_dict = []
  f = open('UncertaintyDict.csv')
  csv_f = csv.reader(f)
  for row in csv_f:
    uncert_dict.append(row[0].lower())

  print(uncert_dict)

def main():
  preprocess_bio()
  preprocess_csv()

if __name__ == "__main__":
  main()

