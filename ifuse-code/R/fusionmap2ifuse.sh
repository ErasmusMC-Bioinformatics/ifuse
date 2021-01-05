#!/bin/bash

if [ $# -ne 2 ] 
then
  echo "usage: $0 <fusionmap file> <outfile> "
fi

awk 'BEGIN {FS="\t"; OFS="\t"; i=0}{
	i++;
	if(FNR<=1){
		print ">Id","LeftChr","LeftPosition","LeftStrand","LeftLength","RightChr","RightPosition","RightStrand","RightLength","StrandConsistent","InterChromosomal","Distance","DiscordantMatePairAlignments","JunctionSequenceResolved","TransitionSequence","TransitionLength","LeftRepeatClassification","RigthRepeatClassification","LeftGenes", "RightGenes", "XRef","DeletedTransposableElement","KnownUnderrepresentedRepeat","FrequencyInBaselineGenomeSet","AssembledSequence", "EventId","Type","relatedJunctions"
	}
	if(FNR>1) {	
		c="N";
		ic="N";
		if(substr($5,1,1) == substr($5,2,2)) c="Y";
		if($6 != $8) ic="Y"
		print i,"chr"$6, $7,substr($5,1,1),"1","chr"$8,$9,substr($5,2,2),"1",c,ic,($9-$7 >=0)? $9-$7 : $7-$9 ,"","N","#","0","","",$11":"$13,$15":"$17,"","","","",$18,"","",""
	}
}' $1 > $2






