import nltk
import pycrfsuite
import re
import os
import nltk
from nltk.corpus import treebank
from nltk.tag import hmm
from nltk.util import unique_list, LazyMap
from nltk.tag import CRFTagger


# Parsing training data into list of lists of word and tag
# Ex [[("I", "B"), ("like", "I"), ("this", "O"), (".", "O")],
#     [("He", "B"), ("needs", "I"), ("coat", "O"), (".", "O")]]
def preprocess_train():
  train_data = []
  for filename in os.listdir("train"):
    sentence = []
    if filename != ".DS_Store":
      with open("train/"+filename, 'r+') as f:
        for line in f.read().splitlines():
          lineSplit = re.split('\t+', line)
          if (len(lineSplit) < 3):
            continue
          else:
            if (lineSplit[0] == ''):
              train_data.append(sentence)
              sentence = []
            else:
              sentence.append((lineSplit[0], lineSplit[2]))
      if(sentence != []):
        train_data.append(sentence)

  # Dirty fix to get rid of empty list. Fix when done.
  if len(train_data[0]) == 0:
    train_data.pop(0)
  return train_data

# Parsing Test data to feed into CRF
def parse_test_data():
  test_data = []
  for filename in os.listdir("test-public"):
    sent = []
    if filename != ".DS_Store":
      with open("test-public/"+filename, 'r+') as f:
        for line in f.read().splitlines():
          lineSplit = re.split('\t+', line)
          if (len(lineSplit) < 1):
            continue
          else:
            if (lineSplit[0] == ''):
              test_data.append(sent)
              sent = []
            else:
              sent.append(lineSplit[0])
      if(sent != []):
        test_data.append(sent)
  return test_data

# Baseline implementation of crf without features
# Will also print kaggle parsed output
def train_baseline_crf(train_data, test_data):
  tagger = CRFTagger()
  tagger.train(train_data, 'model.crf.tagger')

  output = tagger.tag_sents(test_data)

  numlist = []
  sCount= 0

  # sentence level tagging
  for sentence in output:
    for wordpair in sentence:
      if wordpair[1]== 'B':
        if sCount not in numlist:
          numlist.append(sCount)
    sCount = sCount + 1

  print(numlist)

  phraselist = []
  wCount= 0

  # phrase level tagging
  for senten in output:
    for wordp in senten:
      if ((wordp[1] == 'B') or (wordp[1] == 'I')):
        if wCount not in phraselist:
          phraselist.append(wCount)
      wCount = wCount + 1
  print(phraselist)

  start = phraselist[0]
  rangelist = []
  end = 0
  for i in range(len(phraselist)-1):
      if(phraselist[i]+1 == phraselist[i+1]):
          continue
      else:
          end= phraselist[i]
          rangelist.append(str(start) + '-' + str(end))
          start= phraselist[i+1]
  if(i+2 == len(phraselist)):
      end= phraselist[i+1]
      rangelist.append(str(start) + '-' + str(end))
  print(rangelist)

#train the model and tag test data
def main():
  train_data = preprocess_train()
  test_data = preprocess_test()
  crf_output = train_crf(train_data, test_data)


if __name__ == "__main__":
  main()